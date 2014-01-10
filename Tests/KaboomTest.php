<?php

namespace Beryllium\Kaboom\Tests;

use Beryllium\Kaboom\Kaboom;

class KaboomTest extends \PHPUnit_Framework_TestCase
{
    public function testKaboomGoesKaboom()
    {
        $this->setExpectedException('Beryllium\Kaboom\KaboomException');
        error_reporting(E_ALL);
        $kaboom = new Kaboom('prod');
        $kaboom->kaboom();
    }

    public function testConstructorGoesKaboomWhenDevErrorReportingChosenPoorly()
    {
        $this->setExpectedException('Beryllium\Kaboom\KaboomException');
        error_reporting(E_ALL);
        $kaboom = new Kaboom('dev');
    }

    public function testConstructorDoesNotKaboomWhenDevErrorReportingChosenWisely()
    {
        error_reporting(-1);
        $kaboom = new Kaboom('dev');
    }
}
