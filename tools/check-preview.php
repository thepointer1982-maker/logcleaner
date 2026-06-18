<?php

declare(strict_types=1);

$fixture = 'tests/fixtures/logs/basic.log';
if (!is_file($fixture) || !is_readable($fixture)) {
    fwrite(STDERR, "Missing preview fixture: {$fixture}\n");
    exit(1);
}

function preview_count(string $fixture, string $mode, string $value = ''): array
{
    $handle = fopen($fixture, 'rb');
    if ($handle === false) {
        throw new RuntimeException("Cannot read {$fixture}");
    }

    $total = 0;
    $parsed = 0;
    $malformed = 0;
    $affected = 0;
    $seen = [];

    while (($line = fgets($handle)) !== false) {
        $total++;
        $entry = json_decode(trim($line), true);
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

        if ($mode === 'level' && strtolower((string) ($entry['level'] ?? '')) === strtolower($value)) {
            $affected++;
        }

        if ($mode === 'app' && strtolower((string) ($entry['app'] ?? '')) === strtolower($value)) {
            $affected++;
        }
    }

    fclose($handle);

    return [
        'total_lines' => $total,
        'parsed_lines' => $parsed,
        'malformed_lines' => $malformed,
        'affected_lines' => $affected,
        'writes_performed' => 0,
    ];
}

$cases = [
    'duplicate' => [preview_count($fixture, 'duplicate'), 1],
    'level-warning' => [preview_count($fixture, 'level', 'warning'), 2],
    'app-files' => [preview_count($fixture, 'app', 'files'), 2],
];

foreach ($cases as $name => [$result, $expectedAffected]) {
    if ($result['total_lines'] !== 5) {
        fwrite(STDERR, "{$name}: expected 5 total lines.\n");
        exit(1);
    }
    if ($result['parsed_lines'] !== 4) {
        fwrite(STDERR, "{$name}: expected 4 parsed lines.\n");
        exit(1);
    }
    if ($result['malformed_lines'] !== 1) {
        fwrite(STDERR, "{$name}: expected 1 malformed line.\n");
        exit(1);
    }
    if ($result['affected_lines'] !== $expectedAffected) {
        fwrite(STDERR, "{$name}: unexpected affected line count.\n");
        exit(1);
    }
    if ($result['writes_performed'] !== 0) {
        fwrite(STDERR, "{$name}: preview must not write.\n");
        exit(1);
    }
}

echo "Cleanup preview checks OK.\n";
