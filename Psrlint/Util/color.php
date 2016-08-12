<?php

namespace Psrlint\Util;

use Colors\Color;

function color($str)
{
    $color = new Color();
    $color->setTheme([
        'error' => ['red', 'bold'],
    ]);
    return $color($str);
}
