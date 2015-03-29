<?php


namespace Njasm\Soundcloud\Tests\Resolve;

use Njasm\Soundcloud\Resolve\Resolve;

class ResolveTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $status = '302 - Found';
        $location = 'https://localhost/1';

        $resolve = new Resolve($status, $location);

        $this->assertTrue($resolve->found());
        $this->assertEquals('302', $resolve->statusCode());
        $this->assertEquals('Found', $resolve->statusString());
        $this->assertEquals($location, $resolve->location());
        $this->assertEquals($location, $resolve);

    }
}