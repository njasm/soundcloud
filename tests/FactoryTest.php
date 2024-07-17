<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Soundcloud\Factory\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public $factory;
    
    public function setUp(): void
    {
        $this->factory = new Factory();
    }
    
    public function testException()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage("Invalid interface requested");

        $this->factory->make('');
    }
    
    public function testNonExistentInterfaceException()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage("You should register Non-Existent in the Factory first.");

        $this->factory->make('Non-Existent');
    }
    
    public function testHas()
    {
        $this->assertTrue($this->factory->has('AuthInterface'));
        $this->assertFalse($this->factory->has('Non-Existent'));
    }
    
    public function testRegister()
    {
        $returnObj = $this->factory->register("OtherInterface", "Other\\Namespace\\Class");
        $this->assertTrue($returnObj instanceof Factory);
        $this->assertTrue($this->factory->has("OtherInterface"));
    }
    
    public function testMakeWithArgs()
    {
        $obj = $this->factory->make('AuthInterface', array('FakeClientID'));
        $this->assertInstanceOf("Njasm\\Soundcloud\\Auth\\AuthInterface", $obj);
    }
    
    public function testMakeFactory()
    {
        $obj = $this->factory->make('FactoryInterface');
        $this->assertInstanceOf('Njasm\\Soundcloud\\Factory\\FactoryInterface', $obj);
    }
}
