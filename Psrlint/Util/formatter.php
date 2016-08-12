<?php

namespace Psrlint\Util;

use Psrlint\Error;

use function Psrlint\Formatters\defaultFormat;
use function Psrlint\Formatters\yamlFormat;
use function Psrlint\Formatters\jsonFormat;

function formatter($report, $format)
{
    switch ($format) {
        case 'json':
            return jsonFormat($report);
        case 'yaml':
            return yamlFormat($report);
        default:
            return defaultFormat($report);
    }
}
