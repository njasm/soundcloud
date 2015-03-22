<?php

namespace Njasm\Soundcloud\Resource;

class Comment extends AbstractResource
{
    protected $writableProperties = ['body', 'track_id', 'user_id', 'timestamp'];

    public function save()
    {

    }

    public function update()
    {
        throw new \Exception("Can't Update a Comment");
    }

    public function delete()
    {
        throw new \Exception("Can't Delete a Comment");
    }
}