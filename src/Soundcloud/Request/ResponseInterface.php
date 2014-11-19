<?php

namespace Njasm\Soundcloud\Request;

interface ResponseInterface
{
    /**
     * Get Response Headers
     *
     * @return Array the headers as key => value pairs.
     */
    public function getHeaders();

    /**
     * Return header
     *
     * @param  string      $header the asked header
     * @return string|null the header string, if set, null otherwise
     */
    public function getHeader($header);

    /**
     * Check if header exists/is set.
     *
     * @param  type    $header the asked header
     * @return Boolean true if exists, false otherwise
     */
    public function hasHeader($header);

    /**
     * Return the body response in raw string format.
     *
     * @return string the last body response
     */
    public function bodyRaw();

    /**
     * Returns an object based on the Content-Type of the response.
     *
     * @return Object
     */
    public function bodyObject();

    /**
     * Returns an Array based on the Content-Type of the response.
     *
     * @return array
     */
    public function bodyArray();
}
