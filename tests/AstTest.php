<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use PhpParser\Node;
use Psrlint\Ast;

class AstTest extends TestCase
{
    public function testGetAst()
    {
        $code = "<?php";
        $ast = (new Ast($code))->getAst();
        $this->assertInstanceOf(Node::class, $ast[0]);
    }

    public function testGetFunctionNodes()
    {
        $code = '<?php function fn1() {return true;}' .
                      'function fn2() {return true;}';
        $ast = new Ast($code);
        $functionNodes = $ast->getFunctionNodes();
        $this->assertEquals(2, count($functionNodes));
    }
}
