<?php

namespace Psrlint\Tests\RulesTests;

use PHPUnit\Framework\TestCase;
use Psrlint\Rules\FunctionsNaming;
use Psrlint\LintEngine;
use Psrlint\Ast;

class FunctionsNamingTest extends TestCase
{
    protected function setUp()
    {
        $this->ruleInstance = new FunctionsNaming();
    }

    public function testHaveRulesProperty()
    {
        $this->assertObjectHasAttribute('rules', new FunctionsNaming);
    }

    public function testRulesPropertyShape()
    {
        foreach ($this->ruleInstance->rules as $rule) {
            $this->assertArrayHasKey('pattern', $rule);
            $this->assertArrayHasKey('message', $rule);
        }
    }

    public function testThatItWorksCorrectly()
    {
        $code = '<?php ' .
            'function CamelCaps() {return;}' .
            'function _Underscore() {return;}' .
            'function lower_under() {return;}';
        $ast = new Ast($code);
        $lintEngine = new LintEngine($ast, [$this->ruleInstance], 'text');
        $report = $lintEngine->process();
        $this->assertEquals(3, count($report['sourceReport']['errors']));
    }
}
