<?php

namespace Psrlint;

/**
 * Outputs the results of the linting.
 */
class Printer
{
    public function printHelp()
    {
        echo "\n";
        echo "\t\033[34m Help:\n";
        echo "\n";
        return 'Help!';
    }

    public function printReport($report)
    {
        foreach ($report as $r) {
            echo "\n\033[31m Source: \033[0m {$r['source']} \n";
            foreach ($r['sourceReport'] as $type => $filereport) {
                echo "\t\033[32m {$type}:\n";
                foreach ($filereport as $e) {
                    echo "\t\t\033[32m Proplem: \033[34m {$e['name']} \n";
                    echo "\t\t\033[32m Message: \033[0m {$e['message']} \n";
                }
            }
        }
        echo "\n";
        return $report;
    }
}
