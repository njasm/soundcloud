<?php

namespace Njasm\Soundcloud\Factory;

interface FactoryInterface
{
    public function register($interface, $class);
    public function has($interface);
    public function make($interface, array $params = array());
}
