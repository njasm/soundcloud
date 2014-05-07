<?php

use Njasm\Soundcloud\Container\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase 
{
    public $container;
    
    public function setUp()
    {
        $this->container = new Container();
    }
    
    public function testException()
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            "Invalid interface requested"
        );
        $this->container->make('');
    }
    
    public function testNonExistentInterfaceException()
    {
        $this->setExpectedException('\InvalidArgumentException',
            "You should register Non-Existent in the Container first.");
        $this->container->make('Non-Existent');
    }
    
    public function testHas()
    {
        $this->assertTrue($this->container->has('AuthInterface'));
        $this->assertFalse($this->container->has('Non-Existent'));
    }
    
    public function testRegister()
    {
        $returnObj = $this->container->register("OtherInterface", "Other\\Namespace");
        $this->assertTrue($returnObj instanceof Container);
        $this->assertTrue($this->container->has("OtherInterface"));
    }
    
    public function testMakeWithArgs()
    {
        $obj = $this->container->make('AuthInterface', array('FakeClientID'));
        $this->assertTrue($obj instanceof Njasm\Soundcloud\Auth\AuthInterface);
    }

}