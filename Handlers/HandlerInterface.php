<?php

namespace Beryllium\Kaboom\Handlers;

interface HandlerInterface
{
    public function handle(string $message): void;
}