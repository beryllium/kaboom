<?php

namespace Beryllium\Kaboom;

class Kaboom
{
    public function __construct($env = null)
    {
        if (strtolower($env) == 'dev' && error_reporting() != -1) {
            return $this->kaboom();
        }
    }

    public function kaboom()
    {
        throw new KaboomException();
    }
}
