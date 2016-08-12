<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Error;

class ErrorTest extends TestCase
{
    /**
     * @expectedException Psrlint\Error
     */
    public function testError()
    {
        throw new Error();
    }
}
