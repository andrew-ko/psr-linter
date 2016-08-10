<?php

namespace Psrlint\Linter;

use PhpParser\NodeTraverser;
use Psrlint\NodeVisitor;
use Psrlint\Store;
use function Psrlint\Config\defaultRules;
use function Psrlint\Util\createAst;

function inspect($text)
{
    $ast = createAst($text);

    $rules = initRules(defaultRules()); // temporary only default

    $reducer = createReducer($rules);
    $store = new Store($reducer);

    $visitor = new NodeVisitor($store);

    $traverser = new NodeTraverser();
    $traverser->addVisitor($visitor);
    $traverser->traverse($ast);

    return $store->getReport();
}

function createReducer(array $rules)
{
    /**
     * $state  array Messages, result of checking
     * $action array ['type' => visitor.event, 'payload' => [node, tokens, etc...]]
     */
    return function ($state, $action) use ($rules) {
        return array_reduce($rules, function ($acc, $rule) use ($action) {
            if (array_key_exists($action['actionType'], $rule)) {
                $acc[] = $rule[$action['actionType']]($acc, $action['payload']);
            }
            return $acc;
        }, $state);
    };
}

function initRules($rulesList)
{
    return array_map(function ($ruleName) {
        $className = 'Psrlint\\Rules\\' . $ruleName;
        return (new $className)->init();
    }, $rulesList);
}
