<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Printer;

class PrinterTest extends TestCase
{
    public function testPrintReport()
    {

        $this->expectOutputString("\n");
        $printer = new Printer();
        $this->assertSame([], $printer->printReport([]));
    }

    public function testPrintHelp()
    {
        $this->expectOutputString("\n\t\033[34m Help:\n\n");
        $printer = new Printer();
        $printer->printHelp();
    }
}
