<?php

namespace Psrlint\Config;

function defaultRules()
{
    return [
        'FunctionsNaming',
    ];
}

function defaultOptions($key = null)
{
    $opt = [
        '--fix' => false,
        '--output' => null,
        '--stdin' => false,
        '--stdin-filename' => false,
        '--color' => true,
        '--version' => 'v0.0.2',
        'PATH' => [],
    ];

    return $key && $key !== 'PATH' ? $opt['--' . $key] : $opt;
}
