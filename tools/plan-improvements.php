<?php

declare(strict_types=1);

$candidates = [
    ['id' => 'IMP-001', 'title' => 'Cleaning preview helper', 'value' => 5, 'risk' => 2, 'effort' => 2, 'next' => 'Promote existing dry-run spec into helper-level implementation.'],
    ['id' => 'IMP-002', 'title' => 'Malformed-line diagnostics', 'value' => 4, 'risk' => 1, 'effort' => 2, 'next' => 'Add synthetic fixtures and non-sensitive counters.'],
    ['id' => 'IMP-003', 'title' => 'Dashboard health summary', 'value' => 4, 'risk' => 1, 'effort' => 3, 'next' => 'Prototype read-only aggregate helper first.'],
    ['id' => 'IMP-004', 'title' => 'Redacted diagnostic export', 'value' => 5, 'risk' => 3, 'effort' => 3, 'next' => 'Keep proposed until masking fixtures exist.'],
    ['id' => 'IMP-005', 'title' => 'Saved admin filters', 'value' => 3, 'risk' => 1, 'effort' => 2, 'next' => 'Define schema and server-side validation.'],
    ['id' => 'IMP-006', 'title' => 'Importable synthetic fixtures', 'value' => 4, 'risk' => 1, 'effort' => 2, 'next' => 'Create fixture directory and generator rules.'],
    ['id' => 'IMP-007', 'title' => 'Manual test matrix', 'value' => 3, 'risk' => 1, 'effort' => 1, 'next' => 'Document route, preview, package, and release checks.'],
    ['id' => 'IMP-008', 'title' => 'Controller hardening map', 'value' => 5, 'risk' => 2, 'effort' => 2, 'next' => 'Document route-to-method risk before code changes.'],
    ['id' => 'IMP-009', 'title' => 'Large-log benchmark fixture', 'value' => 3, 'risk' => 2, 'effort' => 3, 'next' => 'Only run under extended local profile.'],
    ['id' => 'IMP-010', 'title' => 'Cleanup audit summary', 'value' => 5, 'risk' => 4, 'effort' => 4, 'next' => 'Defer until preview and route hardening are stable.'],
    ['id' => 'IMP-011', 'title' => 'Retention presets', 'value' => 3, 'risk' => 4, 'effort' => 4, 'next' => 'Defer; destructive behavior needs preview first.'],
    ['id' => 'IMP-012', 'title' => 'Package smoke test notes', 'value' => 3, 'risk' => 1, 'effort' => 1, 'next' => 'Add manual checklist for installing generated archive.'],
];

foreach ($candidates as &$candidate) {
    $candidate['score'] = ($candidate['value'] * 10) - ($candidate['risk'] * 4) - ($candidate['effort'] * 2);
}
unset($candidate);

usort($candidates, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

echo "# Improvement plan\n\n";
echo "Score = value*10 - risk*4 - effort*2. Higher is better.\n\n";
echo "| Rank | ID | Title | Score | Next safe step |\n";
echo "| --- | --- | --- | ---: | --- |\n";
$rank = 0;
foreach ($candidates as $candidate) {
    $rank++;
    echo '| ' . $rank . ' | ' . $candidate['id'] . ' | ' . $candidate['title'] . ' | ' . $candidate['score'] . ' | ' . $candidate['next'] . " |\n";
}
