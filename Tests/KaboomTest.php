<?php

namespace Beryllium\Kaboom\Tests;

use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\KaboomException;
use PHPUnit\Framework\TestCase;

class KaboomTest extends TestCase
{
    public function testCustomKaboomWhenConditionTrips(): void
    {
        $this->expectException(KaboomException::class);
        $kaboom = new Kaboom();

        error_reporting(E_ALL);
        $env = 'dev';
        $kaboom->custom(
            'Environment check failed!',
            fn() => strtolower($env) === 'dev' && error_reporting() !== -1
        );
    }

    public function testCustomKaboomWhenConditionFails(): void
    {
        $kaboom = new Kaboom();

        error_reporting(-1);
        $env = 'dev';
        $actual = $kaboom->custom(
            'Environment check failed!',
            fn() => strtolower($env) === 'dev' && error_reporting() !== -1
        );

        $this->assertFalse($actual, "test passed - exception was not thrown");
    }
}
