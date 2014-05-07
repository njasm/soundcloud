<?php

namespace Njasm\Soundcloud\Container;

interface ContainerInterface
{
    public function register($interface, $class);
    public function has($interface);
    public function make($interface, array $params = array());
}
