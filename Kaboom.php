<?php

namespace Beryllium\Kaboom;

class Kaboom
{
    /**
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

    public function todo(string $message, string $date): bool {
        $timestamp = strtotime($date);

        if (time() < $timestamp) {
            return false;
        }

        throw new KaboomException($message);
    }
}
