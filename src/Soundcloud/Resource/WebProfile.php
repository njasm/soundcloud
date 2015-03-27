<?php
/**
 * Created by PhpStorm.
 * User: njasm
 * Date: 22/03/15
 * Time: 21:49
 */

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Soundcloud;

class WebProfile extends AbstractResource
{
    protected $resource = 'web_profile';
    protected $writableProperties = ['title', 'url', 'network'];

    public function refresh()
    {

    }

    public function save()
    {

    }

    public function update()
    {
        $sc = Soundcloud::instance();
        $verb = 'PUT';
        $url = '/';
    }

    public function delete()
    {

    }
}