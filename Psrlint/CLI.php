<?php

namespace Psrlint;

use Psrlint\Engine;
use Psrlint\Error;
use function Psrlint\Util\color;
use function Psrlint\Util\resolvePaths;
use function Psrlint\Config\defaultOptions;
use function Psrlint\Formatters\defaultFormat;

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

            $this->printReport($report);

            return self::EXIT_CODE_NORMAL;
        } catch (Error $e) {
            fwrite(STDERR, color("Psrlint: {$e->getMessage()}")->error . PHP_EOL);
            return self::EXIT_CODE_ERROR;
        }
    }

    protected function initOptions($cmdOptions)
    {
        $options = array_merge(
            defaultOptions(),
            $cmdOptions
        );

        if ($options['--stdin'] && $options['--fix']) {
            throw new Error("The --fix option is not available for piped-in code.");
        }

        return $options;
    }

    protected function printReport($report)
    {
        fwrite(STDOUT, defaultFormat($report) . PHP_EOL);
    }
}
