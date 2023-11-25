<?php

namespace Beryllium\Kaboom\Handlers;

use Beryllium\Kaboom\KaboomException;

/**
 * The Earth-Shattering Kaboom you've been looking for!
 */
class ExceptionHandler implements HandlerInterface
{
    public function handle(string $message): void
    {
        throw new KaboomException($message);
    }
}