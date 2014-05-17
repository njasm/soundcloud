<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Exception\SoundcloudException;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Response implements ResponseInterface
{
    private $httpVersion;
    private $httpCode;
    private $httpCodeString;
    private $response;
    private $info;
    private $errno;
    private $errorString;
    private $headers = array();
    private $body;
    
    public function __construct($response, array $info, $errno, $errorString)
    {
        $this->response = $response;
        $this->info = $info;
        $this->errno = $errno;
        $this->errorString = $errorString;
        
        list($header, $body) = explode("\r\n\r\n", str_replace("HTTP/1.1 100 Continue\r\n\r\n", "", $response), 2);
        $this->body = $body;
        $this->buildHeaderArray($header);
    }
    
    private function buildHeaderArray($header)
    {
        $headers = explode("\n", $header);
        foreach ($headers as $head) {
            if (substr($head, 0, 4) == "HTTP") {
                list($this->httpVersion, $this->httpCode) = explode(" ", $head, 2);
                list($this->httpCode, $this->httpCodeString) = explode(" ", $this->httpCode);
                continue;
            }
            
            list($key, $value) = explode(": ", $head, 2);
            $this->headers[trim($key)] = trim($value);
        }
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function hasHeader($header)
    {
        $header = trim($header);
        if (array_key_exists($header, $this->headers)) {
            return true;
        }
        
        return false;
    }
    
    public function getHeader($header)
    {
        $header = trim($header);
        if (array_key_exists($header, $this->headers)) {
            return $this->headers[$header];
        }
        
        return null;
    }
    
    public function bodyString()
    {
        return $this->body;
    }
    
    public function bodyObject()
    {
        $contentType = $this->getHeader('Content-Type');
        if (stripos($contentType, 'application/json') !== false) {
            return json_decode($this->body);
        } elseif (stripos($contentType, 'application/xml') !== false) {
            return simplexml_load_string($this->body);
        } else {
            throw new \OutOfBoundsException("Last Request Content-Type isn't application/json nor application/xml.");
        }
    }
    
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }
    
    public function getHttpCode()
    {
        return $this->httpCode;
    }
    
    public function getInfo()
    {
        return $this->info;
    }
}
