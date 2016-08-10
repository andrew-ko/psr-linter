<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use function Psrlint\Linter\inspect;
use function Psrlint\Linter\createReducer;

class LinterTest extends TestCase
{
    public function testInspect()
    {
        $this->assertEquals([], inspect('string'));
    }

    public function testCreateReducer()
    {
        $reducer = createReducer([]);
        $this->assertTrue($reducer instanceof \Closure);
        $this->assertSame([], $reducer([], []));
    }
}
