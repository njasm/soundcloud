<?php

namespace Njasm\Soundcloud\Resource;

class Comment extends AbstractResource
{
    protected $resource = 'comment';
    protected $writableProperties = ['body', 'track_id', 'user_id', 'timestamp'];

    public function save(Track $t = null)
    {
        if ($t) {
            $track_id = $t->get('id');
        } else {
            $track_id = is_numeric($this->get('track_id'))
                ? $this->get('track_id')
                : null;
        }

        if (is_null($track_id)) {
            throw new \Exception("Invalid track_id!");
        }

        $this->set('track_id', $track_id);
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