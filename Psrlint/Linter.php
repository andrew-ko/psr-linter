<?php

namespace Psrlint\Linter;

use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter;
use Psrlint\NodeVisitor;
use Psrlint\Store;
use function Psrlint\Config\defaultRules;
use function Psrlint\Util\createAst;

function inspect($text, $fix = false)
{
    $ast = createAst($text);

    $rules = initRules();

    $reducer = createReducer($rules);
    $store = new Store($reducer);

    if ($fix) {
        $store->subscribe(createFixer());
    }

    $visitor = new NodeVisitor($store);

    $traverser = new NodeTraverser();
    $traverser->addVisitor($visitor);
    $newAst = $traverser->traverse($ast);

    if ($fix) {
        $prettyPrinter = new PrettyPrinter\Standard;
        $fixedCode = $prettyPrinter->prettyPrintFile($newAst);
        $fixedResult = inspect($fixedCode)[0];
    }

    return [
        $fixedResult ?? $store->getResult(),
        $fixedCode ?? null
    ];
}

function createReducer(array $rules)
{
    return function ($state, $action) use ($rules) {
        return array_reduce($rules, function ($acc, $rule) use ($action) {
            if (array_key_exists($action['actionType'], $rule)) {
                $message = call_user_func($rule[$action['actionType']], $action['payload']);
                if ($message) {
                    $acc[] = $message;
                }
            }
            return $acc;
        }, $state);
    };
}

function createFixer()
{
    return function ($results, $actionType) {
        if ($actionType === 'traverse.end') {
            foreach ($results as $result) {
                if (isset($result['fix'])) {
                    $result['fix']();
                }
            }
        }
    };
}

function initRules()
{
    $rulesList = defaultRules();

    return array_map(function ($ruleName) {
        $className = 'Psrlint\\Rules\\' . $ruleName;
        return (new $className)->init();
    }, $rulesList);
}
