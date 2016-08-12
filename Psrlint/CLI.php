<?php

namespace Psrlint;

use Psrlint\Engine;
use Psrlint\Error;
use function Psrlint\Util\color;
use function Psrlint\Util\resolvePaths;
use function Psrlint\Util\formatter;
use function Psrlint\Config\defaultOptions;

class CLI
{
    const EXIT_CODE_NORMAL = 0;
    const EXIT_CODE_ERROR  = 1;

    /**
     * @return integer exit code
     */
    public function execute($args, $text = '')
    {
        try {
            $options = $this->initOptions($args);
            $files = resolvePaths($args['PATH'], $options);

            $engine = new Engine($options);

            $report = $text
                ? $engine->executeOnText($text)
                : $engine->executeOnFiles($files);

            if ($options['--fix']) {
                $this->outputFixes($report, $options);
            }
            $this->printReport($report, $options);

            return self::EXIT_CODE_NORMAL;
        } catch (Error $e) {
            fwrite(STDERR, color("Psrlint: {$e->getMessage()}")->error . PHP_EOL);
            return self::EXIT_CODE_ERROR;
        }
    }

    protected function initOptions($cmdOptions)
    {
        return array_merge(
            defaultOptions(),
            $cmdOptions
        );
    }

    protected function outputFixes($report, $options)
    {
        $results = array_filter($report['results'], function ($result) {
            return isset($result['output']);
        });

        foreach ($results as $result) {
            $options['--stdin']
                ? fwrite(STDOUT, color("\nFixed result:\n")->green . $result['output'] . PHP_EOL)
                : file_put_contents($result['filepath'], $result['output']);
        }
    }

    protected function printReport($report, $options)
    {
        $outputFile = $options['--output'];
        $output = formatter($report, $options['--format']);

        $outputFile
            ? file_put_contents($outputFile, $output)
            : fwrite(STDOUT, $output . PHP_EOL);
    }
}
