<?php

namespace Njasm\Soundcloud\Factory;

interface FactoryInterface
{
    /**
     * 
     * @param string $interface
     * @param string $class
     * @return factory 
     */
    public function register($interface, $class);
    
    /**
     * @param string $interface interface name
     * @return boolean
     */
    public function has($interface);
    
    /**
     * 
     * @param string $interface
     * @param array $params
     * @return object mapped to $interface
     *
     * @throws \InvalidArgumentException On interface not registered.
     */
    public function make($interface, array $params = array());
}
