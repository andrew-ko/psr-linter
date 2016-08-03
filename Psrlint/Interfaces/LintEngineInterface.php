<?php

namespace Psrlint\Interfaces;

use Psrlint\Interfaces\RuleInterface;

interface LintEngineInterface
{
    /**
     * @return array Report of linting
     */
    public function process();

    public function accept(RuleInterface $rule);

    public function getAst();

    public function getReport();
    public function setReport(array $report);
}
