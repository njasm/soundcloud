<?php
/**
 * Created by PhpStorm.
 * User: njasm
 * Date: 22/03/15
 * Time: 21:49
 */

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Factory\Factory;
use Njasm\Soundcloud\Soundcloud;

class WebProfile extends AbstractResource
{
    protected $resource = 'web_profile';
    protected $writableProperties = ['title', 'url', 'network'];

    /**
     * Tries to refresh the resource requesting it to Soundcloud based on it's id.
     *
     * @param bool $returnNew
     * @return AbstractResource|void
     * @throws \Exception
     */
    public function refresh($returnNew = false)
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $sc = Soundcloud::instance();
        $userID = $sc->getMe()->get('id');
        $id = $this->get('id');
        $url = '/users/' . $userID . '/web-profiles/' . $id;
        $response = $sc->get($url)->send();

        if ($returnNew) {
            return Factory::unserialize($response->bodyRaw());
        }

        $this->unserialize($response->bodyRaw());
    }

    /**
     * Tries to save the resource in Soundcloud when the resource object is new.
     *
     * @return AbstractResource|void
     * @throws \Exception
     */
    public function save()
    {
        if (!$this->isNew()) {
            throw new \LogicException("Resource is not new.");
        }

        $sc = Soundcloud::instance();
        $userID = $sc->getMe()->get('id');
        $url = '/users/' . $userID . '/web-profiles';
        $response = $sc->post($url, $this->serialize())->send();

        if ($response->getHttpCode() == 200) {
            return $this->unserialize($response->bodyRaw());
        }

        return Factory::unserialize($response->bodyRaw());
    }

    /**
     * Tries to update the resource in Soundcloud based on resource's id.
     *
     * @param bool $refreshState
     * @return AbstractResource|void
     * @throws \Exception
     */
    public function update($refreshState = true)
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $sc = Soundcloud::instance();
        $userID = $sc->getMe()->get('id');
        $id = $this->get('id');
        $url = '/users/' . $userID . '/web-profiles/' . $id;
        $response = $sc->put($url, $this->serialize())->send();

        if ($response->getHttpCode() == 200 && $refreshState) {
            return $this->unserialize($response->bodyRaw());
        }

        return Factory::unserialize($response->bodyRaw());
    }

    /**
     * Tries to delete the resource in Soundcloud based on resource's id.
     *
     * @return AbstractResource|void
     * @throws \Exception
     */
    public function delete()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $sc = Soundcloud::instance();
        $userID = $sc->getMe()->get('id');
        $id = $this->get('id');
        $url = '/users/' . $userID . '/web-profiles/' . $id;
        $response = $sc->delete($url)->send();

        if ($response->getHttpCode() != 200) {
            return Factory::unserialize($response->bodyRaw());
        }

        // clear object state
        $this->properties = [];
    }
}
