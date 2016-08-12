<?php

namespace space;

const BAR = false;

if (true) {
    function foo()
    {
        return true;
    }
    if (true) {
        if (true) {
            foo();
        }
    }
}
