<?php

namespace Njasm\Soundcloud\Factory;

use Njasm\Soundcloud\Factory\FactoryInterface;

class Factory implements FactoryInterface
{
    private $map = array(
        'AuthInterface'         => 'Njasm\\Soundcloud\\Auth\\Auth',
        'RequestInterface'      => 'Njasm\\Soundcloud\\Request\\Request',
        'ResponseInterface'     => 'Njasm\\Soundcloud\\Request\\Response',
        'ResourceInterface'     => 'Njasm\\Soundcloud\\Resource\\Resource',
        'UrlBuilderInterface'   => 'Njasm\\Soundcloud\\UrlBuilder\\UrlBuilder',
        'FactoryInterface'      => 'Njasm\\Soundcloud\\Factory\\Factory'
    );

    public function register($interface, $class)
    {
        $this->validate($interface);
        $this->map[$interface] = $class;

        return $this;
    }

    /**
     * @param string $interface
     * @param array $params
     * @return object
     */
    public function make($interface, array $params = array())
    {
        $this->validate($interface);
        
        if ($this->has($interface) === false) {
            throw new \InvalidArgumentException("You should register $interface in the Factory first.");
        }

        $reflected = new \ReflectionClass($this->map[$interface]);

        if (empty($params)) {
            return $reflected->newInstanceArgs();
        }

        return $reflected->newInstanceArgs($params);
    }
    
    public function has($interface)
    {
        return isset($this->map[$interface]) === true ? true : false;
    }
    
    /**
     * @param string $interface
     * @throws \InvalidArgumentException
     */
    private function validate($interface)
    {
        if (empty($interface)) {
            throw new \InvalidArgumentException("Invalid interface requested");
        }
    }
}
