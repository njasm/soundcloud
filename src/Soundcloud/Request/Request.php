<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use Njasm\Soundcloud\Factory\FactoryInterface;
use Njasm\Soundcloud\Soundcloud;
use Psr\Container\ContainerInterface;

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
    const VERB_GET = 'get';
    const VERB_PUT = 'put';
    const VERB_POST = 'post';
    const VERB_DELETE = 'delete';

    private $resource;
    private $urlBuilder;
    private $container;

    private $options = [
        CURLOPT_HTTPHEADER => [],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 600,
        CURLOPT_HEADER => true
    ];

    private $responseFormat = 'application/json';

    public function __construct(
        ResourceInterface $resource, UrlBuilderInterface $urlBuilder, ContainerInterface $container
    ) {
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @return Request
     */
    public function setOptions(array $options)
    {
        if (!empty($options)) {
            foreach($options as $index => $value) {
                $this->options[$index] = $value;
            }
        }

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

        //curl_setopt_array($curlHandler, $this->options);
        // workaround for issue njasm/soundcloud#28 on github.
        // for some reason curl_setopt_array does not wanna work well with 7.0 on some PHP builds.
        // needs further investigation.
        foreach($this->options as $index => $value) {
            curl_setopt($curlHandler, $index, $value);
        }

        curl_setopt($curlHandler, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($curlHandler, CURLOPT_URL, $this->urlBuilder->getUrl());

        if ($verb != 'GET') {
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $this->getBodyContent());
        }

        $response = curl_exec($curlHandler);
        $info = curl_getinfo($curlHandler);
        $errno = curl_errno($curlHandler);
        $errorString = curl_error($curlHandler);
        curl_close($curlHandler);

        $this->options[CURLOPT_HTTPHEADER] = [];

        return $this->container->get(ResponseInterface::class, [$response, $info, $errno, $errorString]);
    }

    protected function getBodyContent()
    {
        if (in_array('Content-Type: application/json', $this->options[CURLOPT_HTTPHEADER])) {
            return json_encode($this->resource->getParams());
        }

        if (in_array('Content-Type: application/x-www-form-urlencoded', $this->options[CURLOPT_HTTPHEADER])) {
            return http_build_query($this->resource->getParams());
        }

        return $this->resource->getParams();
    }

    protected function buildDefaultHeaders()
    {
        $headers = array('Accept: ' . $this->responseFormat);

        $data = $this->resource->getParams();
        if (isset($data['oauth_token'])) {
            $oauth = $data['oauth_token'];
            array_push($headers, 'Authorization: OAuth ' . $oauth);
        }

        // set default content-type if non-existent
        $found = false;
        array_map(
            function ($value) use (&$found) {
                if (stripos($value, 'content-type') !== false) {
                    $found = true;
                }
            },
            $this->options[CURLOPT_HTTPHEADER]
        );

        if (!$found) {
            array_push($this->options[CURLOPT_HTTPHEADER], "Content-Type: application/json");
        }
        //merge headers
        $this->options[CURLOPT_HTTPHEADER] = array_merge($this->options[CURLOPT_HTTPHEADER], $headers);
    }

    /**
     * @return string the User-Agent string
     */
    public function getUserAgent()
    {
        // Mozilla/5.0 (compatible; Njasm-Soundcloud/2.2.0; +https://www.github.com/njasm/soundcloud)
        $userAgent = "Mozilla/5.0 (compatible; ";
        $userAgent .= Soundcloud::LIB_NAME . '/' . Soundcloud::VERSION . '; +' . Soundcloud::LIB_URL;
        $userAgent .= ')';

        return $userAgent;
    }
}
