<?php
namespace Njasm\Soundcloud\Tests;

trait MocksTrait
{
    public function getFactoryMock($requestMock, $responseMock)
    {
        $factoryMock = $this->getMock("Njasm\\Soundcloud\\Factory\\LibraryFactory", array('build'));
        $factoryMock->expects($this->any())
            ->method('build')
            ->with(
                $this->logicalOr(
                    $this->equalTo('RequestInterface'),
                    $this->equalTo('ResponseInterface')
                )
            )
            ->will(
                $this->returnCallback(
                    function ($arg) use ($requestMock, $responseMock) {
                        if ($arg == 'RequestInterface') {
                            return $requestMock;
                        }

                        return $responseMock;
                    }
                )
            );

        return $factoryMock;
    }

    public function getResponseMock($methodName, $returnCallback)
    {
        $apiResponse = include __DIR__ . '/Data/200_Response.php';
        $responseMock = $this->getMock(
            "Njasm\\Soundcloud\\Http\\Response",
            array($methodName),
            array($apiResponse, [], '', '', '')
        );

        $responseMock->expects($this->any())
            ->method($methodName)
            ->will($this->returnCallback($returnCallback));

        return $responseMock;
    }

    public function getRequestMock($responseMock, $method = 'GET', $url = '/me', array $params = [])
    {
        $requestMock = $this->getMock("Njasm\\Soundcloud\\Http\\Request", array('send'), [$method, $url, $params]);
        $requestMock->expects($this->any())
            ->method('send')
            ->will(
                $this->returnCallback(
                    function () use ($responseMock) {
                        return $responseMock;
                    }
                )
            );

        return $requestMock;
    }
}