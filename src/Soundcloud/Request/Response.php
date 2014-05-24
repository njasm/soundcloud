<?php

namespace Njasm\Soundcloud\Request;

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
        
        $parts = explode("\r\n\r\nHTTP/", $response);
        $parts = (count($parts) > 1 ? 'HTTP/' : '') . array_pop($parts);
        list($headers, $body) = explode("\r\n\r\n", $parts, 2);

        $this->body = $body;
        $this->buildHeadersArray($headers);
    }
    
    private function buildHeadersArray($headers)
    {
        $headers = explode("\n", $headers);
        foreach ($headers as $header) {
            if (substr($header, 0, 4) === "HTTP") {
                list($this->httpVersion, $this->httpCode, $this->httpCodeString) = explode(" ", $header, 3);
                continue;
            }
            
            list($key, $value) = explode(": ", $header, 2);
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
    
    public function bodyRaw()
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
