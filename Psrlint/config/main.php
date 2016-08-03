<?php

$rules = require __DIR__ . '/rules.php';

return [
    'color' => false,
    'format' => false,
    'outputFile' => false,
    'fix' => false,
    'quiet' => false,
    'maxWarnings' => false,
    'rules' => $rules,
];
