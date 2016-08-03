<?php

namespace Psrlint;

use Psrlint\LintEngine;
use Psrlint\Printer;
use Psrlint\Ast;

class CLIProcess
{
    const EXIT_CODE_NORMAL = 0;
    const EXIT_CODE_ERROR = 1;

    protected $config;
    protected $target;
    /* protected $options; */
    protected $printer;

    public function __construct(array $config = [], array $argv = [])
    {
        $this->config = $config;
        $this->target = $argv[1] ?? 'help';
        /* $this->options = $this->initOptions($argv); */
        $this->printer = new Printer();
    }

    /**
     * @return integer exit code
     */
    public function execute()
    {
        if ($this->target === 'help') {
            $this->printer->printHelp();
            return self::EXIT_CODE_NORMAL;
        }

        $sources = $this->getSources($this->target);
        $rules = $this->getRulesInstances();

        $report = [];

        foreach ($sources as $source) {
            $ast = new Ast($source->getCode());
            $engine = new LintEngine($ast, $rules, $source->getName());
            $report[] = $engine->process();
        }


        if ($this->printer->printReport($report)) {
            return self::EXIT_CODE_NORMAL;
        }

        return self::EXIT_CODE_ERROR;
    }

    protected function getSources($target)
    {
        $sources = [];
        $t = rtrim($target, '/');
        if (is_dir($t)) {
            foreach (glob("{$t}/*.*") as $filename) {
                $sources[] = new Source($filename);
            }
        } else {
            $sources[] = new Source($t);
        }
        return $sources;
    }

    protected function getRulesInstances()
    {
        $rulesNames = $this->config['rules'];
        $rules = [];
        foreach ($rulesNames as $ruleName) {
            $className = 'Psrlint\\Rules\\' . $ruleName;
            $rules[] = new $className();
        }
        return $rules;
    }

    /* protected function initOptions(array $argv): array */
    /* { */
    /*     $cliArgs = array_filter($argv, function ($idx) { */
    /*         return $idx !== 0 && $idx !== 1; */
    /*     }, ARRAY_FILTER_USE_KEY); */
    /*     $userOptions = []; */
    /*     return array_merge($this->getDefaultOptions(), $userOptions, $cliArgs); */
    /* } */
}
