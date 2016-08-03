<?php

namespace Psrlint;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class Ast
{
    protected $ast;
    protected $cachedNodes;

    public function __construct($code)
    {
        $this->ast = $this->makeAst($code);
    }

    public function getAst()
    {
        return $this->ast;
    }

    public function getFunctionNodes()
    {
        return $this->cachedNodes['functionNodes'] ?? $this->prepareFunctionNodes();
    }

    protected function makeAst($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            return $parser->parse($code);
        } catch (Error $e) {
            echo 'Parse Error: ', $e->getMessage() . PHP_EOL;
        }
    }

    protected function prepareFunctionNodes()
    {
        $functionNodes = [];
        $ast = $this->ast ?? [];

        $this->traverseAst($ast, function ($node) use (&$functionNodes) {
            if ($node instanceof Function_ || $node instanceof ClassMethod) {
                $functionNodes[] = $node;
            }
        });
        $this->cachedNodes['functionNodes'] = $functionNodes;
        return $functionNodes;
    }

    protected function traverseAst($node, $cb)
    {
        foreach ($node as $parent) {
            $cb($parent);
            if (isset($parent->stmts)) {
                $this->traverseAst($parent->stmts, $cb);
            }
        }
    }
}
