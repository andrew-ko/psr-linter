<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Store;
use Psrlint\NodeVisitor;
use function Psrlint\Linter\createReducer;

class NodeVisitorTest extends TestCase
{
    public function testVisitor()
    {
        $store = new Store(createReducer([]));
        $visitor = new NodeVisitor($store);
        $visitor->beforeTraverse([]);
        $node = $this->createMock(\PhpParser\Node::class);
        $visitor->enterNode($node);
        $visitor->leaveNode($node);
        $visitor->afterTraverse([]);
    }
}
