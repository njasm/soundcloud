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

interface RequestInterface
{
    /**
     * Set curl options
     *
     * @param array $options curl options
     */
    public function setOptions(array $options);

    /**
     * Get curl options
     *
     * @return array curl options
     */
    public function getOptions();

    /**
     * Execute a curl request
     *
     * @return Response
     */
    public function send();
}
