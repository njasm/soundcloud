<?php

namespace Njasm\Soundcloud\Tests\Factory;

use \Njasm\Soundcloud\Factory\LibraryFactory;

class LibraryFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterfaceExistsException()
    {
        $this->setExpectedException('\Exception');
        LibraryFactory::build('NonExistentInterface');
    }

    public function testInterfaceClassExistsException()
    {
        $this->setExpectedException('\Exception');
        LibraryFactory::set('TestInterface', __NAMESPACE__ . '\NonExistentClass');
        LibraryFactory::build('TestInterface');
    }

    public function testIsInstantiableException()
    {
        $this->setExpectedException('\Exception');
        LibraryFactory::set('TestInterface', __NAMESPACE__ . '\NonInstantiableClass');
        LibraryFactory::build('TestInterface');
    }

    public function testSet()
    {
        LibraryFactory::set('TestInterface', __NAMESPACE__ . '\InstantiableClass');
        $returnValue = LibraryFactory::build('TestInterface');
        $this->assertInstanceOf(__NAMESPACE__ . '\InstantiableClass', $returnValue);
    }
}

class InstantiableClass {}
abstract class NonInstantiableClass {}