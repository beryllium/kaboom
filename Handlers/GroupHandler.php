<?php

namespace Beryllium\Kaboom\Handlers;

class GroupHandler implements HandlerInterface
{
    protected array $handlers;

    public function __construct(array $handlers)
    {
        foreach ($handlers as $handler) {
            if ($handler instanceof HandlerInterface) {
                $this->handlers[] = $handler;
                continue;
            }

            throw new \RuntimeException("Provided handlers list includes an incompatible handler!");
        }
    }

    public function handle(string $message) {
        foreach ($this->handlers as $handler) {
            $handler->handle($message);
        }
    }
}