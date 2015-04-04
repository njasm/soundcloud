<?php

namespace Njasm\Soundcloud\Factory;

use Njasm\Soundcloud\Collection\Collection;
use Njasm\Soundcloud\Exception\SoundcloudResponseException;
use Njasm\Soundcloud\Resolve\Resolve;
use Njasm\Soundcloud\Soundcloud;

class ApiResponseFactory
{
    public static function unserialize($serialized)
    {
        $data = json_decode($serialized, true, 2048);
        self::decodeIsValid();

        if (empty($data)) {
            return self::collection();
        }

        self::guardAgainstErrors($data);

        if (isset($data['status'])) {
            return self::resolve($data);
        }

        if (isset($data[0]) && is_array($data[0])) {
            $collection = self::collection($data[0]['kind']);
            return self::addItemsToCollection($collection, $data);
        }

        return self::resource($serialized);
    }

    /**
     * Validates if json_decode was successful.
     *
     * @throws \Exception
     * @return void
     */
    protected static function decodeIsValid()
    {
        $message = '';
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return;
                break;
            case JSON_ERROR_DEPTH:
                $message = ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $message = ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $message = ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $message = ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $message = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $message = ' - Unknown error';
                break;
        }

        if (!empty($message)) {
            throw new \Exception("json decode error $message");
        }
    }

    protected static function guardAgainstErrors(array $data)
    {
        if (isset($data['errors'])) {
            throw new SoundcloudResponseException($data);
        }
    }

    public static function collection($kind = '')
    {
        $kind = self::getClass($kind);
        $collectionClass = "\\Njasm\\Soundcloud\\Collection\\" . $kind . "Collection";
        if (class_exists($collectionClass)) {
            return new $collectionClass;
        }

        throw new \Exception("$collectionClass non-existent.");
    }

    protected static function addItemsToCollection(Collection $collection, array $data)
    {
        foreach($data as $line) {
            $resource = self::resource($line);
            $collection->add($resource);
        }

        return $collection;
    }

    /**
     * @param $line
     * @return \Njasm\Soundcloud\Resource\AbstractResource
     * @throws \Exception
     */
    public static function resource($line)
    {
        if (!is_array($line)) {
            $line = json_decode($line, true);
        }

        $sc = Soundcloud::instance();
        $kind = self::getClass($line['kind']);
        $resourceClass = "\\Njasm\\Soundcloud\\Resource\\" . $kind;

        return new $resourceClass($sc, $line);
    }

    public static function resolve($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }

        return new Resolve($data['status'], $data['location']);
    }

    protected static function getClass($kind)
    {
        $parts = explode("-", $kind);
        $kind = '';
        foreach((array) $parts as $part) {
            $kind .= ucfirst($part);
        }

        return $kind;
    }
}