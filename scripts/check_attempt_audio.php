<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IeltsTestAttempt;

$attemptId = $argv[1] ?? 227;
$attempt = IeltsTestAttempt::find($attemptId);
if (! $attempt) {
    echo json_encode(['error' => 'attempt_not_found', 'attempt_id' => $attemptId]);
    exit(0);
}

$test = $attempt->test;
if (! $test) {
    echo json_encode(['error' => 'test_not_found', 'attempt_id' => $attemptId]);
    exit(0);
}

$sections = $test->sections()->where('skill', 'listening')->get();
$out = ['attempt_id' => $attempt->id, 'test_id' => $test->id, 'sections' => []];
foreach ($sections as $s) {
    $out['sections'][] = [
        'id' => $s->id,
        'title' => $s->title,
        'audio_file' => $s->audio_file,
        'audio_url' => $s->audio_url,
    ];
}

echo json_encode($out, JSON_PRETTY_PRINT);
