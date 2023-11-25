<?php

namespace Beryllium\Kaboom\Handlers;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Kaboom's LoggingHandler
 *
 * It may not deliver an earth-shattering Kaboom,
 * but it should help you log & respond to Kaboom messages.
 */
class LoggingHandler implements HandlerInterface, LoggerAwareInterface
{
    protected LoggerInterface $logger;
    protected string $level;

    public function __construct(LoggerInterface $logger, string $level = LogLevel::WARNING)
    {
        $this->setLogger($logger)->setLoggerLevel($level);
    }

    public function handle(string $message): void
    {
        $this->logger->{$this->level}($message);
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    private function setLoggerLevel(string $level): self
    {
        $this->level = match ($level) {
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG => $level,
            default => LogLevel::WARNING
        };

        return $this;
    }
}