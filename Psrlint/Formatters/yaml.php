<?php

namespace Psrlint\Formatters;

use Symfony\Component\Yaml\Yaml;

function yamlFormat($report)
{
    return Yaml::dump($report);
}
