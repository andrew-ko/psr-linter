<?php

namespace Psrlint\Rules;

class FunctionsNaming
{
    public function init()
    {
        return [
            'Stmt_Function' => function ($payload) {
                return $this->checkName($payload['node']);
            },
            'Stmt_ClassMethod' => function ($payload) {
                return $this->checkName($payload['node']);
            }
        ];
    }

    private function checkName($node)
    {
        $name = $node->name;

        // ignore magic methods
        if (in_array($name, $this->ignore)) {
            return;
        }

        foreach ($this->rules as $rule) {
            if (preg_match($rule['pattern'], $name)) {
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

    private $ignore = [
        '__construct',
        '__destruct',
        '__call',
        '__callstatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__tostring',
        '__set_state',
        '__clone',
        '__invoke',
        '__debuginfo',
    ];
}
