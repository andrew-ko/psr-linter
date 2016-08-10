<?php

namespace Psrlint\Formatters;

use function Psrlint\Util\color;

function defaultFormat($report)
{
    $errorCount = $report['errorCount'];
    $warningCount = $report['warningCount'];
    $resultsCount = count($report['results']);
    $summary = $errorCount + $warningCount;

    $output = '';
    $output .= PHP_EOL;
    foreach ($report['results'] as $result) {
        if (in_array(true, ($result['messages']))) {
            $output .= color($result['filepath'])->magenta->underLine . PHP_EOL;
            foreach ($result['messages'] as $message) {
                if ($message) {
                    $output .= lineFormat($message['line']);
                    $output .= typeFormat($message['type']);
                    $output .= $message['message'] . PHP_EOL;
                }
            }
            $output .= PHP_EOL;
        }
    }
    $output .= pluralize($resultsCount, 'file') . ' inspected, ';
    $output .= color(pluralize($summary, 'problem'))->red->bold;
    $output .= " detected";
    $output .= PHP_EOL;

    return $output;
}

function typeFormat($type)
{
    $str = str_pad($type, 10);

    return $type === 'error'
        ? color($str)->red
        : color($str)->yellow;
}

function lineFormat($line)
{
    $str = str_pad("line:$line", 10);
    return color($str)->magenta->dark;
}

function formatProblemCode($code)
{
    $lenght = strlen($code);
    $output =  $code . PHP_EOL;
    $output .= str_pad('', $lenght, '^') . PHP_EOL;
    return $output;
}

function pluralize($n, $str)
{
    if ($n !== 1) {
        return "$n {$str}s";
    }
    return "$n $str";
}
