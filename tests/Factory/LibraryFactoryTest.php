<?php

namespace Njasm\Soundcloud\Tests\Factory;

use \Njasm\Soundcloud\Factory\LibraryFactory;

class LibraryFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new LibraryFactory();
    }

    public function testInterfaceExistsException()
    {
        $this->setExpectedException('\Exception');
        $this->factory->build('NonExistentInterface');
    }

    public function testInterfaceClassExistsException()
    {
        $this->setExpectedException('\Exception');
        $this->factory->set('TestInterface', __NAMESPACE__ . '\NonExistentClass');
        $this->factory->build('TestInterface');
    }

    public function testIsInstantiableException()
    {
        $this->setExpectedException('\Exception');
        $this->factory->set('TestInterface', __NAMESPACE__ . '\NonInstantiableClass');
        $this->factory->build('TestInterface');
    }

    public function testSet()
    {
        $this->factory->set('TestInterface', __NAMESPACE__ . '\InstantiableClass');
        $returnValue = $this->factory->build('TestInterface');
        $this->assertInstanceOf(__NAMESPACE__ . '\InstantiableClass', $returnValue);
    }
}

class InstantiableClass {}
abstract class NonInstantiableClass {}