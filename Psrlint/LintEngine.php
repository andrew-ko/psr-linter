<?php

namespace Psrlint;

use Psrlint\Interfaces\LintEngineInterface;
use Psrlint\Interfaces\RuleInterface;
use Psrlint\Ast;

class LintEngine implements LintEngineInterface
{
    protected $ast = [];
    protected $rules = [];
    protected $report = [];

    public function __construct(Ast $ast, $rules, $sourceName)
    {
        $this->ast = $ast;
        $this->rules = $rules;
        $this->report = [
            'source' => $sourceName,
            'sourceReport' => [
                'errors' => [],
                'warnings' => [],
                'fixes' => []
            ]
        ];
    }

    public function process()
    {
        foreach ($this->rules as $rule) {
            $this->accept($rule);
        }

        return $this->getReport();
    }

    public function accept(RuleInterface $rule)
    {
        $rule->visit($this);
    }

    public function getAst()
    {
        return $this->ast;
    }

    public function getReport(): array
    {
        return $this->report;
    }

    public function setReport(array $report)
    {
        $this->report['sourceReport'] = array_merge_recursive(
            $this->report['sourceReport'],
            $report
        );
    }
}
