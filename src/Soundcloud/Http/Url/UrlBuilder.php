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
    /**
     * {@inheritdoc}
     */
    public static function getUrl($verb, $uri, array $params = [])
    {
        $uri = self::getCleanPath($uri);
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
    private static function getCleanPath($path)
    {
        if (substr($path, strlen($path) - 1) === "/") {
            $path = substr($path, 0, strlen($path) - 1);
        }

        return $path;
    }
}
