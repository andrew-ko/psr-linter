<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Interfaces\RuleInterface;
use Psrlint\LintEngine;
use Psrlint\Ast;

class LintEngineTest extends TestCase
{
    protected $engine;

    protected function setUp()
    {
        $rules = [];
        $code = "<?php function functionName() {return true;};";
        $ast = new Ast($code);
        $sourceName = 'text';
        $this->engine = new LintEngine($ast, $rules, $sourceName);
    }

    public function testProcess()
    {
        $report = $this->engine->process();
        $this->assertTrue((bool) $report);
    }

    /**
     * @expectedException TypeError
     * @expectedExceptionMessage must implement interface Psrlint\Interfaces\RuleInterface
     */
    public function testAccept()
    {
        $this->engine->accept('');
    }

    public function testGetAst()
    {
        $this->assertInstanceOf(Ast::class, $this->engine->getAst());
    }

    public function testGetReport()
    {
        $reportShape = [
            'source' => 'text',
            'sourceReport' => [
                'errors' => [],
                'warnings' => [],
                'fixes' => []
            ]
        ];
        $this->assertArraySubset($reportShape, $this->engine->getReport());
    }

    public function testSetReport()
    {
        $report = [
            'errors' => [
                'name' => 'name',
                'message' => 'message'
            ]
        ];
        $this->engine->setReport($report);

        $this->assertArraySubset(
            [
                'source' => 'text',
                'sourceReport' => [
                    'errors' => [
                        'name' => 'name',
                        'message' => 'message'
                    ],
                    'warnings' => [],
                    'fixes' => []
                ]
            ],
            $this->engine->getReport()
        );
    }
}
