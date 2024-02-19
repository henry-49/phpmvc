<?php

declare(strict_types=1);

namespace Framework;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $registry = [];

    // closure is used to represent aunonymously function
    public function set(string $name, Closure $value): void
    {
        $this->registry[$name] = $value;
    }

    
    public function get(string $class_name): object
    {
        // check if array key exists in registry
        if (array_key_exists($class_name, $this->registry)){

            return $this->registry[$class_name]();
        }

        // create object from the controller class
        $reflector = new ReflectionClass($class_name);

        $constructor = $reflector->getConstructor();

        $dependencies = [];

        if ($constructor === null) {

            return new $class_name;
        }
        
        foreach ($constructor->getParameters() as $parameter) {

            $type =  $parameter->getType();

            if ($type === null) {
                throw new InvalidArgumentException("constructor parameter '{$parameter->getName()}'
                        in the $class_name class has no type declaration");

            }

            if (! ($type instanceof ReflectionNamedType)) {
                throw new InvalidArgumentException("constructor parameter '{$parameter->getName()}'
                 in the $class_name class is an invalid type: '$type' 
                 - only single name types supported");
            }

            // output message if a class cannot be created automatically
            if ($type->isBuiltIn()){
                throw new InvalidArgumentException("Unable to resolve constructor parameter 
                '{$parameter->getName()} of type '$type' in the $class_name class");
            }

            $dependencies[] = $this->get((string) $type);
        }

        return new $class_name(...$dependencies);
    }
}