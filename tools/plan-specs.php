<?php
/**
 * Plan approved specs without executing them.
 *
 * This dependency-free helper ranks *.spec.md files by priority, dependencies,
 * and estimated complexity so maintainers can choose the next safe change.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$specDirs = [
    $root . '/specs',
    $root . '/specs/templates',
];
$priorityCsv = $root . '/spec-priorities.csv';

function readPriorityRules(string $path): array {
    if (!is_file($path)) {
        return [];
    }

    $handle = fopen($path, 'rb');
    if ($handle === false) {
        fwrite(STDERR, "Cannot read {$path}\n");
        exit(1);
    }

    $rules = [];
    while (($row = fgetcsv($handle)) !== false) {
        if ($row === [] || str_starts_with(trim((string)$row[0]), '#')) {
            continue;
        }
        if (count($row) < 4 || $row[0] === 'spec_filename') {
            continue;
        }

        $rules[$row[0]] = [
            'priority' => max(1, min(20, (int)$row[1])),
            'dependencies' => $row[2] !== '' ? array_filter(array_map('trim', explode(';', $row[2]))) : [],
            'complexity' => in_array($row[3], ['low', 'medium', 'high'], true) ? $row[3] : 'medium',
        ];
    }
    fclose($handle);

    return $rules;
}

function extractListCount(string $content, string $key): int {
    if (!preg_match('/^' . preg_quote($key, '/') . ':\s*$/m', $content, $match, PREG_OFFSET_CAPTURE)) {
        return 0;
    }

    $offset = $match[0][1] + strlen($match[0][0]);
    $rest = substr($content, $offset);
    $count = 0;
    foreach (preg_split('/\R/', $rest) as $line) {
        if (preg_match('/^[A-Za-z0-9_-]+:\s*/', $line)) {
            break;
        }
        if (preg_match('/^\s*-\s+/', $line)) {
            $count++;
        }
    }

    return $count;
}

function parseInlinePriority(string $content): ?int {
    if (!preg_match('/^priority:\s*(low|medium|high|critical|\d+)\s*$/mi', $content, $match)) {
        return null;
    }

    $value = strtolower($match[1]);
    return match ($value) {
        'low' => 4,
        'medium' => 8,
        'high' => 14,
        'critical' => 20,
        default => max(1, min(20, (int)$value)),
    };
}

function inferComplexity(string $content): string {
    $scopeCount = extractListCount($content, 'scope_in');
    if ($scopeCount <= 2) {
        return 'low';
    }
    if ($scopeCount <= 5) {
        return 'medium';
    }
    return 'high';
}

function complexityScore(string $complexity): int {
    return match ($complexity) {
        'low' => 1,
        'medium' => 2,
        'high' => 3,
        default => 2,
    };
}

function recommendedProfile(string $complexity): string {
    return match ($complexity) {
        'low' => 'fast',
        'medium' => 'standard',
        'high' => 'deep',
        default => 'standard',
    };
}

$priorityRules = readPriorityRules($priorityCsv);
$specs = [];

foreach ($specDirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    foreach (glob($dir . '/*.spec.md') ?: [] as $path) {
        $content = file_get_contents($path);
        if ($content === false || !preg_match('/^status:\s*approved\s*$/mi', $content)) {
            continue;
        }

        $name = basename($path);
        $rule = $priorityRules[$name] ?? null;
        $priority = parseInlinePriority($content) ?? ($rule['priority'] ?? 5);
        $complexity = $rule['complexity'] ?? inferComplexity($content);
        $dependencies = $rule['dependencies'] ?? [];
        $openDependencies = 0;
        foreach ($dependencies as $dependency) {
            foreach ($specDirs as $dependencyDir) {
                $dependencyPath = $dependencyDir . '/' . $dependency;
                if (is_file($dependencyPath)) {
                    $dependencyContent = file_get_contents($dependencyPath) ?: '';
                    if (preg_match('/^status:\s*approved\s*$/mi', $dependencyContent)) {
                        $openDependencies++;
                    }
                    break;
                }
            }
        }

        $score = ($priority * 10) + (complexityScore($complexity) * 2) - ($openDependencies * 15);
        $specs[] = [
            'name' => $name,
            'path' => str_replace($root . '/', '', $path),
            'priority' => $priority,
            'complexity' => $complexity,
            'open_dependencies' => $openDependencies,
            'profile' => recommendedProfile($complexity),
            'score' => $score,
        ];
    }
}

usort($specs, static fn(array $a, array $b): int => $b['score'] <=> $a['score'] ?: strcmp($a['name'], $b['name']));

if ($specs === []) {
    echo "No approved specs found.\n";
    exit(0);
}

echo "score\tpriority\tcomplexity\tprofile\topen_deps\tpath\n";
foreach ($specs as $spec) {
    printf(
        "%d\t%d\t%s\t%s\t%d\t%s\n",
        $spec['score'],
        $spec['priority'],
        $spec['complexity'],
        $spec['profile'],
        $spec['open_dependencies'],
        $spec['path']
    );
}
