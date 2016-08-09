<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Engine;
use function Psrlint\Config\defaultOptions;

class EngineTest extends TestCase
{
    protected function setUp()
    {
        $this->engine = new Engine(defaultOptions());
    }

    public function testExecuteOnText()
    {
        $text = '<?php namespace Test;';

        $expected['results'][0]['filepath'] = '<text>';
        $this->assertArraySubset($expected, $this->engine->executeOnText($text));
    }

    public function testExecuteOnFiles()
    {
        $files = [
            __DIR__ . '/fixtures/files/file1.php',
            __DIR__ . '/fixtures/files/file2.php',
        ];

        $results = $this->engine->executeOnFiles($files);

        $this->assertEquals($files[0], $results['results'][0]['filepath']);
        $this->assertEquals($files[1], $results['results'][1]['filepath']);
    }
}
