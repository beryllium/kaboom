<?php

namespace Beryllium\Kaboom\Handlers;

/**
 * GroupHandler allows you to declare multiple handlers for messages.
 *
 * They will be invoked in order of declaration,
 * so make sure to put any code-terminating ones at the end.
 */
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

    public function handle(string $message): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($message);
        }
    }
}