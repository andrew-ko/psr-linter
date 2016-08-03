<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\CLIProcess;

class CLIProcessTest extends TestCase
{
    protected function setUp()
    {
        $this->config = require __DIR__ . '/../Psrlint/config/main.php';
        $this->file = __DIR__ . '/fixtures/functions/file1.php';
        /* $this->dir = require __DIR__ . '/fixtures/functions'; */
    }

    public function testExecute()
    {
        $cli = new CLIProcess();
        $this->assertSame(CLIProcess::EXIT_CODE_NORMAL, $cli->execute());
        $this->expectOutputRegex('/Help/');
    }

    public function testExecuteOnText()
    {
        $cli = new CLIProcess($this->config, ['', '<?php']);
        $this->assertSame(CLIProcess::EXIT_CODE_NORMAL, $cli->execute());
        $this->expectOutputRegex('/Executed on text/');
    }

    public function testExecuteOnFile()
    {
        /* $file = __DIR__ . '/fixtures/functions/file1.php'; */
        $cli = new CLIProcess($this->config, ['', $this->file]);
        $this->assertSame(CLIProcess::EXIT_CODE_NORMAL, $cli->execute());
        $this->expectOutputRegex('/fixtures\/functions\/file1.php/');
    }

    /* public function testExecuteOnDir() */
    /* { */

    /* } */
}
