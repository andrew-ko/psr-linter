<?php

namespace Psrlint\Tests\RulesTests;

use PHPUnit\Framework\TestCase;
use Psrlint\Rules\VarNaming;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;

class VarNamingTest extends TestCase
{

    protected function setUp()
    {
        $this->rule = new VarNaming();
        $this->listeners = $this->rule->init();
    }

    public function testInit()
    {
        foreach ($this->listeners as $listener) {
            $this->assertTrue($listener instanceof \Closure);
        }
        $this->assertArrayHasKey('Stmt_PropertyProperty', $this->listeners);
        $this->assertArrayHasKey('Expr_Variable', $this->listeners);
    }


    public function testThatItWorks()
    {
        $nodes[] = new Variable('CamelCaps');
        $nodes[] = new Variable('_Underscore');
        $nodes[] = new Variable('snake_case');
        $nodes[] = new Variable('camelCase');
        $nodes[] = new PropertyProperty('CamelCaps');
        $nodes[] = new PropertyProperty('_Underscore');
        $nodes[] = new PropertyProperty('snake_case');
        $nodes[] = new PropertyProperty('camelCase');

        foreach ($nodes as $node) {
            $results[] = call_user_func($this->listeners[$node->getType()], ['node' => $node]);
        }

        $warnings = array_filter($results, function ($el) {
            return $el['type'] === 'warning';
        });

        $errors = array_filter($results, function ($el) {
            return $el['type'] === 'error';
        });

        $this->assertEquals(8, count($results));
        $this->assertEquals(2, count($warnings));
        $this->assertEquals(4, count($errors));
    }
}
