<?php

declare(strict_types=1);

$options = getopt('', ['file:', 'mode:', 'value::', 'help']);

if (isset($options['help']) || !isset($options['file'], $options['mode'])) {
    fwrite(STDERR, "Usage: php tools/preview-cleanup.php --file=tests/fixtures/logs/basic.log --mode=duplicate|level|app [--value=warning]\n");
    exit(isset($options['help']) ? 0 : 1);
}

$file = (string) $options['file'];
$mode = (string) $options['mode'];
$value = isset($options['value']) ? (string) $options['value'] : '';

if (!in_array($mode, ['duplicate', 'level', 'app'], true)) {
    fwrite(STDERR, "Unsupported preview mode: {$mode}\n");
    exit(1);
}

if (($mode === 'level' || $mode === 'app') && $value === '') {
    fwrite(STDERR, "Mode {$mode} requires --value.\n");
    exit(1);
}

if (!is_file($file) || !is_readable($file)) {
    fwrite(STDERR, "Preview input is not readable: {$file}\n");
    exit(1);
}

$handle = fopen($file, 'rb');
if ($handle === false) {
    fwrite(STDERR, "Cannot open preview input: {$file}\n");
    exit(1);
}

$total = 0;
$parsed = 0;
$malformed = 0;
$affected = 0;
$seen = [];

while (($line = fgets($handle)) !== false) {
    $total++;
    $trimmed = trim($line);
    if ($trimmed === '') {
        continue;
    }

    $entry = json_decode($trimmed, true);
    if (!is_array($entry)) {
        $malformed++;
        continue;
    }

    $parsed++;

    if ($mode === 'duplicate') {
        $signature = json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($signature === false) {
            $malformed++;
            continue;
        }
        if (isset($seen[$signature])) {
            $affected++;
        } else {
            $seen[$signature] = true;
        }
        continue;
    }

    if ($mode === 'level') {
        $level = isset($entry['level']) ? strtolower((string) $entry['level']) : '';
        if ($level === strtolower($value)) {
            $affected++;
        }
        continue;
    }

    if ($mode === 'app') {
        $app = isset($entry['app']) ? strtolower((string) $entry['app']) : '';
        if ($app === strtolower($value)) {
            $affected++;
        }
    }
}

fclose($handle);

$result = [
    'mode' => $mode,
    'value' => $value,
    'total_lines' => $total,
    'parsed_lines' => $parsed,
    'malformed_lines' => $malformed,
    'affected_lines' => $affected,
    'writes_performed' => 0,
];

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
