<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\CLI;
use Psrlint\Error;
use function Psrlint\Config\defaultOptions;

class CLITest extends TestCase
{
    protected function setUp()
    {
        $this->args = [
            'PATH' => []
        ];
        $this->cli = new CLI();
    }

    public function testExecute()
    {
        $this->assertSame(CLI::EXIT_CODE_NORMAL, $this->cli->execute($this->args));
    }

    public function testExecuteOnFile()
    {
        $this->args['PATH'][] = __DIR__ . '/fixtures/files/file1.php';
        $this->assertSame(CLI::EXIT_CODE_NORMAL, $this->cli->execute($this->args));
    }

    public function testExecuteOnText()
    {
        $this->assertSame(CLI::EXIT_CODE_NORMAL, $this->cli->execute($this->args, '<?php'));
    }

    public function testDefaultOptions()
    {
        $this->assertSame(CLI::EXIT_CODE_NORMAL, $this->cli->execute(defaultOptions()));
    }
}
