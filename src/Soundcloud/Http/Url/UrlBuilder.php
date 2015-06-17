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

class UrlBuilder implements UrlBuilderInterface
{
    protected static $baseUrl = 'https://api.soundcloud.com';

    /**
     * {@inheritdoc}
     */
    public static function url($verb, $uri, array $params = [])
    {
        $uri = self::buildBaseUrl($uri);
        $verb = strtoupper($verb);

        if ($verb === 'GET' && empty($params) !== true) {
            $uri .= '?' . http_build_query($params);
        }

        return $uri;
    }

    /**
     * @param string $path
     * @return string
     */
    private static function cleanPath($path)
    {
        if (substr($path, strlen($path) - 1) === "/") {
            $path = substr($path, 0, strlen($path) - 1);
        }

        return $path;
    }

    protected static function buildBaseUrl($url)
    {
        if (strtolower(substr($url, 0, 4)) != 'http' && $url[0] != '/') {
            $base = self::$baseUrl;
            $uri = '/' . self::cleanPath($url);
            $url = $base . $uri;
        }

        if ($url[0] == '/') {
            $url = self::$baseUrl . self::cleanPath($url);
        }

        return $url;
    }

    public static function setBaseUrl($url)
    {
        self::$baseUrl = $url;
    }

    public static function baseUrl()
    {
        return self::$baseUrl;
    }
}
