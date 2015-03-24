<?php

namespace Njasm\Soundcloud\Exception;

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

class SoundcloudResponseException extends \Exception
{
    /** @var array */
    protected $errors;

    public function __construct(array $errors, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorsAsArray()
    {
        return $this->errors['errors'];
    }

    public function getErrorsAsString($delimiter = ", ")
    {
        $return = [];
        foreach($this->errors['errors'] as $value) {
            $return[] = $value['error_message'];
        }

        return implode($delimiter, $return);
    }
}