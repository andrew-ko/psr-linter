<?php

namespace Psrlint\Interfaces;

use Psrlint\Interfaces\LintEngineInterface;

interface RuleInterface
{
    public function visit(LintEngineInterface $lintEngine);
}
