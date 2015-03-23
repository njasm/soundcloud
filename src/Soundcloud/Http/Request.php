<?php

namespace Njasm\Soundcloud\Http;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 * @since       3.0.0
 */

class Request implements RequestInterface
{
    /** @var string */
    private $verb;
    /** @var string */
    private $url;
    /** @var array */
    private $params = [];

    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HEADER => true
    );

    private $responseFormat = 'application/json';

    public function __construct($verb, $url, array $params = [])
    {
        $this->verb = $verb;
        $this->url = $url;
        $this->params = $params;
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
     * @return ResponseInterface
     */
    public function send()
    {
        $verb = strtoupper($this->verb);

        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array('Accept: ' . $this->responseFormat));
        curl_setopt_array($curlHandler, $this->options);
        curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($curlHandler, CURLOPT_URL, $this->url);

        if ($verb !== 'GET') {
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $this->params);
        }

        curl_setopt($curlHandler, CURLOPT_VERBOSE, true);

        $response = curl_exec($curlHandler);
        $info = curl_getinfo($curlHandler);
        $errno = curl_errno($curlHandler);
        $errorString = curl_error($curlHandler);
        curl_close($curlHandler);

        echo "NEW REQUEST: " . $response;

        return new Response($response, $info, $errno, $errorString);

    }
}