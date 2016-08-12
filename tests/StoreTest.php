<?php

namespace Psrlint\Tests;

use PHPUnit\Framework\TestCase;
use Psrlint\Store;
use function Psrlint\Linter\createReducer;
use function Psrlint\Linter\defaultRules;

class StoreTest extends TestCase
{
    public function testDispatch()
    {
        $reducer = createReducer([]);
        $store = new Store($reducer);
        $store->dispatch('', []);
        $this->assertEquals([], $store->getResult());
    }
}
