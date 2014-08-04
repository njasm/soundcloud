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
        
        list($headers, $body) = $this->buildHeaderParts($response);

        $this->buildHeadersArray($headers);        
        $this->body = $body;
    }
    
    private function buildHeaderParts($response)
    {
        $parts = explode("\r\n\r\nHTTP/", $response);
        $parts = (count($parts) > 1 ? 'HTTP/' : '') . array_pop($parts);
        
        return explode("\r\n\r\n", $parts, 2);
    }
    
    private function buildHeadersArray($headers)
    {
        $headers = explode("\n", $headers);
        $httHead = array_shift($headers);
        list($this->httpVersion, $this->httpCode, $this->httpCodeString) = explode(" ", $httHead, 3);
        
        foreach ($headers as $header) {  
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
        return (array_key_exists($header, $this->headers));
    }
    
    public function getHeader($header)
    {
        return ($this->hasHeader($header)) ? $this->headers[$header] : null;
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
        }
        
        if (stripos($contentType, 'application/xml') !== false) {
            return simplexml_load_string($this->body);
        }
        
        throw new \OutOfBoundsException("Last Request Content-Type isn't application/json nor application/xml.");
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
    
    public function getErrorNo()
    {
        return $this->errno;
    }
    
    public function getErrorString()
    {
        return $this->errorString;
    }
    
    public function getRaw()
    {
        return $this->response;
    }
    
    public function getHttpCodeString()
    {
        return $this->httpCodeString;
    }
}
