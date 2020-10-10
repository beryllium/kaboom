<?php

namespace Beryllium\Kaboom;

use Beryllium\Kaboom\Handlers\ExceptionHandler;
use Beryllium\Kaboom\Handlers\HandlerInterface;

/**
 * Enables force feedback in your code. In a manner of speaking.
 */
class Kaboom
{
    protected HandlerInterface $handler;

    public function __construct(?HandlerInterface $handler = null)
    {
        $this->handler = $handler ?? new ExceptionHandler();
    }

    /**
     * Throw an exception when a custom condition is tripped
     *
     * @param string   $message
     * @param callable $condition
     *
     * @return bool
     * @throws KaboomException
     */
    public function custom(string $message, callable $condition): bool {
        if (!$condition()) {
            return false;
        }

        $this->handler->handle($message);

        return true;
    }

    /**
     * Throw an exception once the provided $date has been passed
     *
     * @param string $message   The message to embed in the exception
     * @param string $date      A date string compatible with strftime()
     *
     * @return bool
     * @throws KaboomException
     */
    public function todo(string $message, string $date): bool {
        $timestamp = strtotime($date);

        if (time() < $timestamp) {
            return false;
        }

        $this->handler->handle($message);

        return true;
    }
}
