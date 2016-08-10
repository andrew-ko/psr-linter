<?php

namespace Psrlint;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Psrlint\Store;

class NodeVisitor extends NodeVisitorAbstract
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->store->dispatch('traverse.start', [
            'ast' => $nodes,
        ]);
    }

    public function enterNode(Node $node)
    {
        $this->store->dispatch($node->getType(), [
            'node' => $node,
        ]);
    }

    public function leaveNode(Node $node)
    {
        $this->store->dispatch($node->getType() . '.leave', [
            'node' => $node,
        ]);
    }

    public function afterTraverse(array $nodes)
    {
        $this->store->dispatch('traverse.end', [
            'ast' => $nodes,
        ]);
    }
}
