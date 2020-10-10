<?php

namespace Beryllium\Kaboom\Tests;

use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\KaboomException;
use PHPUnit\Framework\TestCase;

class KaboomTest extends TestCase
{
    public function testKaboomGoesKaboom()
    {
        $this->expectException(KaboomException::class);
        error_reporting(E_ALL);
        $kaboom = new Kaboom('prod');
        $kaboom->kaboom();
    }

    public function testConstructorGoesKaboomWhenDevErrorReportingChosenPoorly()
    {
        $this->expectException(KaboomException::class);
        error_reporting(E_ALL);
        $kaboom = new Kaboom('dev');
    }

    public function testConstructorDoesNotKaboomWhenDevErrorReportingChosenWisely()
    {
        error_reporting(-1);
        $kaboom = new Kaboom('dev');
        $this->assertTrue(true, "test passed - exception was not thrown");
    }
}
