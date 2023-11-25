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
        $kaboom->condition(
            fn () => strtolower($env) === 'dev' && error_reporting() !== -1,
            fn () => 'Environment check failed!'
        );
    }

    public function testCustomKaboomWhenConditionFails(): void
    {
        $kaboom = new Kaboom();

        error_reporting(-1);
        $env = 'dev';
        $actual = $kaboom->condition(
            fn () => strtolower($env) === 'dev' && error_reporting() !== -1,
            fn () => 'Environment check failed!'
        );

        $this->assertFalse($actual, "test passed - exception was not thrown");
    }

    public function testKaboom_AfterMessage_Trips_For_PastDate(): void {
        $this->expectException(KaboomException::class);
        $kaboom = new Kaboom();

        $kaboom->afterMessage(
            "You have one week to fix this before Canadian Thanksgiving! KAB-201",
            "2020-10-05"
        );
    }

    public function testKaboom_AfterMessage_DoesNotTrip_For_FutureDate(): void {
        $kaboom = new Kaboom();

        $actual = $kaboom->afterMessage(
            "+2 Days",
            "This todo can be postponed indefinitely. No ticket assigned."
        );

        $this->assertFalse($actual, "test passed - exception was not thrown");
    }

    public function testKaboom_BeforeMessage_Trips_For_FutureDate(): void {
        $this->expectException(KaboomException::class);
        $kaboom = new Kaboom();

        $kaboom->beforeMessage(
            "+2 Days",
            "Alert! Alert! This code should not have been invoked! Related to project KAB-202"
        );
    }

    public function testKaboom_BeforeMessage_DoesNotTrip_For_PastDate(): void {
        $kaboom = new Kaboom();

        $actual = $kaboom->beforeMessage(
            "-2 Days",
            "Once the provided date has passed, it becomes okay for this code to have been invoked."
        );

        $this->assertFalse($actual, "test passed - exception was not thrown");
    }

    public function testKaboom_LoggingHandler_Receives_Entries(): void {
        $logger = new class extends AbstractLogger {
            public array $logs = [];

            public function log($level, $message, array $context = array())
            {
                $this->logs[$level][] = ['message' => $message, 'context' => $context];
            }
        };

        $message = "You have one week to fix this before Canadian Thanksgiving! KAB-201";

        $kaboom = new Kaboom(new LoggingHandler($logger));
        $kaboom->afterMessage(
            "2020-10-05",
            $message
        );

        $this->assertSame($message, $logger->logs['warning'][0]['message']);
    }

    public function testKaboom_NullHandler_Does_Nothing(): void {
        $message = "You have one week to fix this before Canadian Thanksgiving! KAB-201";

        $kaboom = new Kaboom(new NullHandler());
        $kaboom->afterMessage(
            "2020-10-05",
            $message
        );

        $this->assertTrue(true, 'nothing happened. good.');
    }

    public function testKaboom_GroupHandler_Sends_To_Each_Handler_In_Group(): void {
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

        $message = "You have one week to fix this before Canadian Thanksgiving! KAB-201";

        $kaboom = new Kaboom($groupHandler);
        $kaboom->afterMessage(
            "2020-10-05",
            $message
        );
    }
}
