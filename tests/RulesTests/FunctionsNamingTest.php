<?php

namespace Psrlint\Tests\RulesTests;

use PHPUnit\Framework\TestCase;
use Psrlint\Rules\FunctionsNaming;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;

class FunctionsNamingTest extends TestCase
{

    protected function setUp()
    {
        $this->rule = new FunctionsNaming();
        $this->listeners = $this->rule->init();
    }

    public function testInit()
    {
        foreach ($this->listeners as $listener) {
            $this->assertTrue($listener instanceof \Closure);
        }
        $this->assertArrayHasKey('Stmt_Function', $this->listeners);
        $this->assertArrayHasKey('Stmt_ClassMethod', $this->listeners);
    }


    public function testThatItWorks()
    {
        $nodes[] = new ClassMethod('CamelCaps');
        $nodes[] = new ClassMethod('_Underscore');
        $nodes[] = new ClassMethod('snake_case');
        $nodes[] = new ClassMethod('camelCase');
        $nodes[] = new Function_('CamelCaps');
        $nodes[] = new Function_('_Underscore');
        $nodes[] = new Function_('snake_case');
        $nodes[] = new Function_('camelCase');

        foreach ($nodes as $node) {
            $results[] = $this->listeners[$node->getType()]([], ['node' => $node]);
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
