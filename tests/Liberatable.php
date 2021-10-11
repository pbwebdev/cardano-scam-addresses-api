<?php

namespace Tests;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

trait Liberatable
{
    /**
     * Get the private method using ReflectionClass
     *
     * @param  object|string  $objectOrClass
     * @param  string         $name
     *
     * @return null|ReflectionMethod
     */
    public function getPrivateMethod($objectOrClass, string $name): ?ReflectionMethod
    {
        try {
            $reflector = new ReflectionClass($objectOrClass);

            $method = $reflector->getMethod($name);

            $method->setAccessible(true);
        } catch (ReflectionException $exception) {
            $method = new class () {
                public function __call($name, $arguments)
                {
                    return null;
                }
            };
        }

        return $method;
    }

    /**
     * Get the private property using ReflectionClass
     *
     * @param  object|string  $objectOrClass
     * @param  string         $name
     *
     * @return null|ReflectionProperty
     */
    public function getPrivateProperty($objectOrClass, string $name): ?ReflectionProperty
    {
        try {
            $reflector = new ReflectionClass($objectOrClass);

            $property = $reflector->getProperty($name);

            $property->setAccessible(true);
        } catch (ReflectionException $exception) {
            $property = null;
        }

        return $property;
    }
}
