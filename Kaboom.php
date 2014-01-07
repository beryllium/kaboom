<?php

namespace Beryllium\Kaboom;

class Kaboom
{
    public function __construct($env = null)
    {
        if ($env != 'dev' && error_reporting() != -1) {
            $this->kaboom();
        }
    }

    public function kaboom()
    {
        die("kaboom\n");
    }
}
