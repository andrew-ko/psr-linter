<?php
// 2 errors, 1 warning ['FunctionsNaming']

namespace qq;

class B
{
    private function camelCase()
    {
        return true;
    }

    private function CamelCaps()
    {
        return true;
    }

    private function snake_case()
    {
        return true;
    }

    private function _lodash()
    {
        return $str;
    }
}
