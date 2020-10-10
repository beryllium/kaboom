<?php

namespace Beryllium\Kaboom\Handlers;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggingHandler implements HandlerInterface, LoggerAwareInterface
{
    protected LoggerInterface $logger;
    protected string $level;

    public function __construct(LoggerInterface $logger, string $level = LogLevel::WARNING)
    {
        $this->setLogger($logger)->setLoggerLevel($level);
    }

    public function handle(string $message) {
        $this->logger->{$this->level}($message);
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    private function setLoggerLevel(string $level): self
    {
        $levels = [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ];

        if (!in_array($level, $levels, true)) {
            $level = LogLevel::WARNING;
        }

        $this->level = $level;

        return $this;
    }
}