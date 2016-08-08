<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use function Psrlint\Formatters\defaultFormat;

class DefaultFormatterTest extends TestCase
{
    protected function setUp()
    {
        $this->resultShape = [
            'results' => [
                [
                    'filepath' => 'test/filepath/file.php',
                    'messages' => [
                        [
                            'type' => 'error', // (error|warning)
                            'line' => 2,
                            'message' => ''
                        ]
                    ],
                    'errorCount' => 1,
                    'warningCount' => 0
                ],
            ],
            'errorCount' => 1,
            'warningCount' => 0,
        ];
    }

    public function testFormat()
    {
        $this->expectOutputRegex('/test\/filepath\/file.php/');
        $this->expectOutputRegex('/1 file/');
        $this->expectOutputRegex('/1 problem/');
        $this->expectOutputRegex('/error/');
        $this->expectOutputRegex('/2/');
        echo defaultFormat($this->resultShape);
    }
}
