<?php

namespace Psrlint\Rules;

class VarNaming
{
    public function init()
    {
        return [
            'Expr_Variable' => function ($state, $payload) {
                return $this->checkName($payload['node']);
            },
            'Stmt_PropertyProperty' => function ($state, $payload) {
                return $this->checkName($payload['node']);
            }
        ];
    }

    private function checkName($node)
    {
        foreach ($this->rules as $rule) {
            if (preg_match($rule['pattern'], $node->name)) {
                return [
                    'type' => $rule['type'],
                    'line' => $node->getAttribute('startLine'),
                    'message' => $rule['message']
                ];
            }
        }
    }

    private $rules = [
        [
            'pattern' => '/^_/',
            'message' =>
                'Property and variables names SHOULD NOT be prefixed with a single underscore.',
            'type' => 'warning'
        ],
        [
            'pattern' => '/^[A-Z]|[a-z][_]/',
            'message' => 'Variables names MUST be declared in camelCase().',
            'type' => 'error'
        ]
    ];
}
