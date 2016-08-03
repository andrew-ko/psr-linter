<?php

namespace Psrlint;

class Source
{
    protected $code;
    protected $name;

    public function __construct($target)
    {
        if (is_file($target)) {
            $this->code = file_get_contents(rtrim($target, '/'));
            $this->name = $target;
        } else {
            $this->code = $target;
            $this->name = 'Executed on text';
        }
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }
}
