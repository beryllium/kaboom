<?php

namespace Beryllium\Kaboom\Handlers;

use Beryllium\Kaboom\KaboomException;

class ExceptionHandler implements HandlerInterface
{
    public function handle(string $message): bool {
        throw new KaboomException($message);
    }
}