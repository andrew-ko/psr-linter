<?php

namespace Psrlint\Rules;

use PhpParser\Node\Expr;

class SideEffects
{
    public function init()
    {
        return [
            'traverse.start' => function ($payload) {
                if ($this->hasConflicts($payload['ast'])) {
                    return $this->errorReport;
                }
            },
            'Stmt_Namespace' => function ($payload) {
                if ($this->hasConflicts($payload['node']->stmts)) {
                    return $this->errorReport;
                }
            }
        ];
    }

    private function hasConflicts(array $nodes)
    {
        list($side, $decl) = $this->findBoth($nodes);
        return $side && $decl;
    }

    private function findBoth($nodes): array
    {
        $sideEffects = 0;
        $declarations = 0;

        foreach ($nodes as $node) {
            $type = $node->getType();

            if ($node instanceof Expr || $type === 'Stmt_Echo') {
                $sideEffects++;
            }

            if (in_array($type, $this->declarationsNodes)) {
                $declarations++;
            }

            if ($type === 'Stmt_If') {
                list($s, $d) = $this->findBoth($node->stmts);
                $sideEffects += $s;
                $declarations += $d;
            }
        }

        return [
            $sideEffects,
            $declarations
        ];
    }

    private $errorReport  = [
        'type' => 'warning',
        'line' => 1,
        'message' => 'A file SHOULD declare new symbols (classes, functions, constants, etc.), '.
        'or it SHOULD execute logic with side effects, but SHOULD NOT do both.'
    ];

    private $declarationsNodes = [
        'Stmt_Class',
        'Stmt_Function',
        'Stmt_Const'
    ];
}
