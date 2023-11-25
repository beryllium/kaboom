<?php

namespace Beryllium\Kaboom;

use Beryllium\Kaboom\Handlers\ExceptionHandler;
use Beryllium\Kaboom\Handlers\HandlerInterface;

/**
 * Emits messages or runs code based on provided conditions.
 *
 * Message behaviour is configured using Handlers.
 *
 * For a gentle approach, the LoggingHandler will send the message to your logging system.
 *
 * If not specified, Kaboom uses an ExceptionHandler that emits exceptions.
 */
class Kaboom
{
    protected HandlerInterface $handler;

    public function __construct(?HandlerInterface $handler = null)
    {
        $this->handler = $handler ?? new ExceptionHandler();
    }

    /**
     * Executes the specified wrapped code when the callable condition is tripped
     *
     * If `$execute()` returns a string, the output will be sent to the Handler. For example,
     * if a failure occurs, the returned string would cause Kaboom to Log or throw an Exception.
     *
     * Otherwise, true is returned to signify that the wrapped code was executed, or false is returned
     * to indicate that the condition did not trip.
     *
     * Exampole:
     *
     *   Kaboom Exception when the current environment is Dev but error reporting is not configured
     *   to report all errors:
     *
     *      $kaboom = new Kaboom(); // Exception handler is configured by default
     *      $kaboom->condition(
     *          fn () => getenv('ENV') === 'dev' && error_reporting() !== -1,
     *          fn () => 'Error reporting should be enabled for ALL ERRORS in dev mode!'
     *      );
     *
     * @param callable $condition   A callable that returns true or false, to determine whether to execute
     * @param callable $execute     When $condition() returns true, this code is executed
     *
     * @return bool
     * @throws KaboomException      Depending on the configured Handler
     */
    public function condition(callable $condition, callable $execute): bool
    {
        if (!$condition()) {
            return false;
        }

        $output = $execute();

        // Wrapped code that returns a string shall be sent to the Handler.
        if (is_string($output)) {
            $this->handler->handle($output);
        }

        return true;
    }

    /**
     * Trip a condition after the provided $date has passed
     *
     * @param string $date      A date string compatible with strtotime()
     * @param string $message   The message to embed in the condition result
     *
     * @return bool
     * @throws KaboomException  Depending on the configured Handler
     */
    public function afterMessage(string $date, string $message): bool
    {
        return $this->condition(fn () => time() > strtotime($date), fn () => $message);
    }

    /**
     * Trip a condition before the provided $date has passed
     *
     * @param string $date      A date string compatible with strtotime()
     * @param string $message   The message to embed in the condition result
     *
     * @return bool
     * @throws KaboomException  Depending on the configured Handler
     */
    public function beforeMessage(string $date, string $message): bool
    {
        return $this->condition(fn () => time() < strtotime($date), fn () => $message);
    }
}
