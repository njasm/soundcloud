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
    
    public function make($interface, array $params = array())
    {
        $this->validate($interface);
        
        if ($this->has($interface)) {
            
            $reflected = new \ReflectionClass($this->map[$interface]);
            
            if (empty($params)) {
                return $reflected->newInstanceArgs();
            } else {
                return $reflected->newInstanceArgs($params);
            }
        } else {
            throw new \InvalidArgumentException("You should register $interface in the Factory first.");
        }
    }
    
    public function has($interface) 
    {
        return isset($this->map[$interface]) ? true : false;  
    }
    
    private function validate($interface) 
    {
        if (empty($interface)) {
            throw new \InvalidArgumentException("Invalid interface requested");            
        }
    }
}
