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

interface RequestInterface
{
    public const VERB_GET = 'get';
    public const VERB_PUT = 'put';
    public const VERB_POST = 'post';
    public const VERB_DELETE = 'delete';

    /**
     * Set curl options
     */
    public function setOptions(array $options);
    
    /**
     * Get curl options
     */
    public function getOptions();
    
    /**
     * Execute a curl request
     */
    public function exec();
    
    /**
     * Sets the request to accept XML response
     */
    public function asXml();
    
    /**
     * Sets the request to accept JSON response
     */
    public function asJson();
}
