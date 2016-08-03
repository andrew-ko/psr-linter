<?php

namespace Psrlint\Rules;

use Psrlint\Interfaces\LintEngineInterface;
use Psrlint\Interfaces\RuleInterface;
use Psrlint\Ast;

class FunctionsNaming implements RuleInterface
{
    public $rules = [
        [
            'pattern' => '/^_/',
            'message' =>
            'Method names SHOULD NOT be prefixed with a single underscore to indicate protected or private visibility.'
        ],
        [
            'pattern' => '/^[A-Z]|[a-z][_]/',
            'message' => 'Method names MUST be declared in camelCase().'
        ]
    ];

    public function visit(LintEngineInterface $lintEngine)
    {
        $ast = $lintEngine->getAst();

        $report = $this->processAst($ast);

        $lintEngine->setReport($report);
    }

    protected function processAst(Ast $ast): array
    {
        $functionNodes = $ast->getFunctionNodes();

        $report = [
            'errors' => [],
            'warnings' => [],
            'fixes' => []
        ];

        foreach ($functionNodes as $node) {
            foreach ($this->rules as $rule) {
                if (preg_match($rule['pattern'], $node->name)) {
                    $report['errors'][] = [
                        'name' => $node->name,
                        'message' => $rule['message']
                    ];
                }
            }
        }

        return $report;
    }
}
