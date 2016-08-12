<?php

namespace Psrlint\Rules;

class VarNaming
{
    public function init()
    {
        return [
            'Expr_Variable' => function ($payload) {
                return $this->checkName($payload['node']);
            },
            'Stmt_PropertyProperty' => function ($payload) {
                return $this->checkName($payload['node']);
            }
        ];
    }

    private function checkName($node)
    {
        foreach ($this->rules as $rule) {
            if (preg_match($rule['pattern'], $node->name)) {
                return [
                    'type'    => $rule['type'],
                    'line'    => $node->getAttribute('startLine'),
                    'message' => $rule['message'],

                    'fix'     => function () use ($node) {
                        return $node->name = $this->fixName($node->name);
                    }
                ];
            }
        }
    }

    private function fixName($name)
    {
        return preg_replace_callback_array(
            [
                '/^_/' => function ($match) {
                    return '';
                },
                '/_[a-z|A-Z]/' => function ($match) {
                    return strtoupper(substr($match[0], 1));
                },
                '/^[A-Z]+/' => function ($match) {
                    return strtolower($match[0]);
                },
            ],
            $name
        );
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
