<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use Njasm\Soundcloud\Factory\FactoryInterface;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Request implements RequestInterface
{
    private $resource;
    private $urlBuilder;
    private $factory;
    private $headers = array();

    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HEADER => true
    );

    private $responseFormat = 'application/json';
    
    public function __construct(ResourceInterface $resource, UrlBuilderInterface $urlBuilder, FactoryInterface $factory)
    {
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        $this->factory = $factory;
    }
    
    /**
     * {@inheritdoc}
     * 
     * @return Request
     */
    public function setOptions(array $options)
    {
        $this->options = $options + $this->options; 
        return $this;
    }
    
    /**
     * {@inheritdoc}
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * {@inheritdoc}
     *
     * @deprecated Soundcloud does not support XML responses anymore.
     * @see https://github.com/njasm/soundcloud/issues/16
     *
     * @return Request
     */
    public function asXml()
    {
        $this->asJson();
        return $this;
    }
    
    /**
     * {@inheritdoc}
     *
     * @deprecated Soundcloud does not support XML responses anymore and calling this method is redundant.
     * @see https://github.com/njasm/soundcloud/issues/16
     *
     * @return Request
     */    
    public function asJson()
    {
        $this->responseFormat = 'application/json';
        return $this;
    }
    
    /**
     * {@inheritdoc}
     * 
     * @return ResponseInterface
     */
    public function exec()
    {
        $verb = strtoupper($this->resource->getVerb());
        $this->buildDefaultHeaders();

        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt_array($curlHandler, $this->options);
        curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($curlHandler, CURLOPT_URL, $this->urlBuilder->getUrl());
        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $this->getBodyContent());

        curl_setopt($curlHandler, CURLOPT_VERBOSE, true);
        $response = curl_exec($curlHandler);
        $info = curl_getinfo($curlHandler);
        $errno = curl_errno($curlHandler);
        $errorString = curl_error($curlHandler);
        curl_close($curlHandler);

        return $this->factory->make('ResponseInterface', array($response, $info, $errno, $errorString));
    }

    protected function getBodyContent()
    {
        return json_encode($this->resource->getParams());
    }

    protected function buildDefaultHeaders()
    {
        $this->headers = array('Accept: ' . $this->responseFormat);
        array_push($this->headers, 'Content-Type: application/json');

        $data = $this->resource->getParams();
        if (isset($data['oauth_token'])) {
            $oauth = $data['oauth_token'];
            array_push($this->headers, 'Authorization: OAuth ' . $oauth);
        }
    }
}
