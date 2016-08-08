<?php

namespace Psrlint\Rules;

class FunctionsNaming
{
    public function init()
    {
        return [
            'Stmt_Function' => function ($state, $payload) {
                return $this->checkName($payload['node']);
            },
            'Stmt_ClassMethod' => function ($state, $payload) {
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
            'Method names SHOULD NOT be prefixed with a single underscore to indicate protected or private visibility.',
            'type' => 'warning'
        ],
        [
            'pattern' => '/^[A-Z]|[a-z][_]/',
            'message' => 'Method names MUST be declared in camelCase().',
            'type' => 'error'
        ]
    ];
}
