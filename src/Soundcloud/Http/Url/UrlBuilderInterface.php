<?php

namespace Njasm\Soundcloud\Http\Url;

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

interface UrlBuilderInterface
{
    /**
     * Return the Url.
     *
     * @param $verb
     * @param $uri
     * @param array $params
     * @return string the url
     */
    public static function url($verb, $uri, array $params = []);
}