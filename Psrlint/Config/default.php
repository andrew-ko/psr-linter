<?php

namespace Psrlint\Config;

function defaultRules()
{
    return [
        'FunctionsNaming',
        'VarNaming',
        'SideEffects'
    ];
}

function defaultOptions($key = null)
{
    $opt = [
        '--fix' => false,
        '--output' => null,
        '--ignore' => null,
        '--stdin' => false,
        '--stdin-filename' => false,
        '--color' => true,
        '--version' => 'v0.0.2',
        '--format' => 'default',
        'PATH' => [],
    ];

    return $key && $key !== 'PATH' ? $opt['--' . $key] : $opt;
}
