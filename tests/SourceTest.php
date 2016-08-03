<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Source;

class SourceTest extends TestCase
{
    protected function setUp()
    {
        $this->file = __DIR__ . '/fixtures/functions/file1.php';
        $this->fileSource = new Source($this->file);
        $this->textSource = new Source('<?php');
    }

    public function testGetCode()
    {
        $this->assertEquals('<?php', $this->textSource->getCode());
        $this->assertStringEqualsFile($this->file, $this->fileSource->getCode());
    }

    public function testGetName()
    {
        $this->assertEquals('Executed on text', $this->textSource->getName());
        $this->assertEquals($this->file, $this->fileSource->getName());
    }
}
