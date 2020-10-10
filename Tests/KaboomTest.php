<?php

namespace Beryllium\Kaboom\Tests;

use Beryllium\Kaboom\Handlers\GroupHandler;
use Beryllium\Kaboom\Handlers\LoggingHandler;
use Beryllium\Kaboom\Handlers\NullHandler;
use Beryllium\Kaboom\Kaboom;
use Beryllium\Kaboom\KaboomException;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

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

    public function testKaboomTodoTrips(): void {
        $this->expectException(KaboomException::class);
        $kaboom = new Kaboom();

        $kaboom->todo(
            "This todo needs to be fixed before Thanksgiving! KAB-201",
            "2020-10-05"
        );
    }

    public function testKaboomTodoDoesNotTrip(): void {
        $kaboom = new Kaboom();

        $actual = $kaboom->todo(
            "This todo can be postponed indefinitely. No ticket assigned.",
            "+2 Days"
        );

        $this->assertFalse($actual, "test passed - exception was not thrown");
    }

    public function testKaboomLoggingHandler(): void {
        $logger = new class extends AbstractLogger {
            public array $logs = [];

            public function log($level, $message, array $context = array())
            {
                $this->logs[$level][] = ['message' => $message, 'context' => $context];
            }
        };

        $message = "This todo needs to be fixed before Thanksgiving! KAB-201";

        $kaboom = new Kaboom(new LoggingHandler($logger));
        $kaboom->todo(
            $message,
            "2020-10-05"
        );

        $this->assertSame($message, $logger->logs['warning'][0]['message']);
    }

    public function testKaboomNullHandler(): void {
        $message = "This todo needs to be fixed before Thanksgiving! KAB-201";

        $kaboom = new Kaboom(new NullHandler());
        $kaboom->todo(
            $message,
            "2020-10-05"
        );

        $this->assertTrue(true, 'nothing happened. good.');
    }

    public function testKaboomGroupHandler(): void {
        $mockLogger = $this->createMock(AbstractLogger::class);
        $mockNull   = $this->createMock(NullHandler::class);

        $mockLogger->expects($this->once())->method('warning');
        $mockNull->expects($this->once())->method('handle');

        $groupHandler = new GroupHandler(
            [
                new LoggingHandler($mockLogger),
                $mockNull
            ]
        );

        $message = "This todo needs to be fixed before Thanksgiving! KAB-201";

        $kaboom = new Kaboom($groupHandler);
        $kaboom->todo(
            $message,
            "2020-10-05"
        );
    }
}
