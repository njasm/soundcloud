<?php
namespace Njasm\Soundcloud\Tests;

trait MocksTrait
{
    public function getSoundcloudMock()
    {
        $soundcloudMock = $this->getMock("Njasm\\Soundcloud\\Soundcloud", ['getMe'], ["ClientID", "ClientSecret"]);
        $soundcloudMock->expects($this->any())
            ->method('getMe')
            //->with($this->equalTo('id'))
            ->will($this->returnCallback(
                function () {
                    $class = new \stdClass();
                    $class->get = function () { return 1; };
                }
            ));

        return $soundcloudMock;
    }

    public function getFactoryMock($requestMock, $responseMock)
    {
        $factoryMock = $this->getMock("Njasm\\Soundcloud\\Factory\\LibraryFactory", ['build']);
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

    public function getRequestMock($responseMock, $method = 'GET', $url = 'http://127.0.0.1/me', array $params = [])
    {
        $requestMock = $this->getMock("Njasm\\Soundcloud\\Http\\Request", ['send'], [$method, $url, $params]);
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