<?php

namespace Mashvp;

abstract class SingletonClass
{
    protected static $instances = [];

    final public static function instance()
    {
        $class = static::class;

        if (!isset(static::$instances[$class])) {
            static::$instances[$class] = new static();
        }

        return static::$instances[$class];
    }

    final private function __construct()
    {
    }

    final protected function __clone()
    {
    }
}
