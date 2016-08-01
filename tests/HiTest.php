<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Hi;

class HiTest extends TestCase
{
    public function testHi()
    {
        $hi = new Hi();
        $this->assertEquals(
            'Hi!',
            $hi->hi()
        );
    }
}
