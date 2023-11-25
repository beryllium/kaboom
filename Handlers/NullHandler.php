<?php

namespace Beryllium\Kaboom\Handlers;

/**
 * A Null Handler for Kaboom. Eats messages for breakfast.
 */
class NullHandler implements HandlerInterface
{
    public function handle(string $message): void {}
}