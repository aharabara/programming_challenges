<?php

class EventBus
{
    protected static $listeners = [];

    /**
     * @param string $event
     * @param array $params
     */
    public static function dispatch(string $event, array $params): void
    {
        foreach (self::$listeners[$event] ?? [] as $listener){
            $listener(...$params);
        }
    }

    /**
     * @param string $eventName
     * @param callable $callback
     */
    public static function listen(string $eventName, callable $callback): void
    {
        self::$listeners[$eventName][] = $callback;
    }

}