<?php

namespace Beryllium\Kaboom;

/**
 * Enables force feedback in your code. In a manner of speaking.
 */
class Kaboom
{
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

        throw new KaboomException($message);
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

        throw new KaboomException($message);
    }
}
