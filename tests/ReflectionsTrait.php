<?php
namespace Njasm\Soundcloud\Tests;

trait ReflectionsTrait
{
    public function reflectProperty($class, $property)
    {
        $property = new \ReflectionProperty($class, $property);
        $property->setAccessible(true);

        return $property;
    }
}