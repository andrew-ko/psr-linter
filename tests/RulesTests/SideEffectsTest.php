<?php

namespace Psrlint\Tests\RulesTests;

use PHPUnit\Framework\TestCase;
use Psrlint\Rules\SideEffects;
use Psrlint\Store;
use Psrlint\NodeVisitor;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

use function Psrlint\Linter\createReducer;

class SideEffectsTest extends TestCase
{
    protected function setUp()
    {
        $this->rule = new SideEffects();
        $this->listeners = $this->rule->init();
    }

    public function testInit()
    {
        foreach ($this->listeners as $listener) {
            $this->assertTrue($listener instanceof \Closure);
        }
        $this->assertArrayHasKey('traverse.start', $this->listeners);
        $this->assertArrayHasKey('Stmt_Namespace', $this->listeners);
    }


    public function testThatItWorks()
    {
        $file1 = file_get_contents(__DIR__ . '/../fixtures/files/sideEffects/flatten.php');
        $file2 = file_get_contents(__DIR__ . '/../fixtures/files/sideEffects/ns.php');
        $file3 = file_get_contents(__DIR__ . '/../fixtures/files/sideEffects/nsAndIfs.php');
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $trees[] = $parser->parse($file1);
        $trees[] = $parser->parse($file2);
        $trees[] = $parser->parse($file3);

        foreach ($trees as $ast) {
            $results[] = call_user_func($this->listeners['traverse.start'], ['ast' => $ast]);
            $results[] = call_user_func($this->listeners['Stmt_Namespace'], ['node' => $ast[0]]);
        }

        $warnings = array_filter($results, function ($el) {
            return $el['type'] === 'warning';
        });

        $errors = array_filter($results, function ($el) {
            return $el['type'] === 'error';
        });

        $this->assertEquals(3, count($warnings));
        $this->assertEquals(0, count($errors));
    }
}
