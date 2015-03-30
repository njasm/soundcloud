<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Factory\AbstractFactory;

class User extends AbstractResource
{
    protected $resource = 'user';
    protected $writableProperties = [
        'permalink', 'username', 'country', 'city', 'first_name', 'last_name', 'description'
    ];

    /*****************
     * SUB RESOURCES *
     *****************/

    public function getTracks()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/tracks';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getPlaylists()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/playlists';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function  getFollowings()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/followings';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFollowing($id)
    {
        if (!is_int($id)) {
            throw \Exception("Following id is not an integer");
        }

        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/followings/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Followers of User.
     *
     * @return Collection
     */
    public function getFollowers()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/followers';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get follower of User by id.
     *
     * @param int $id the follower id
     * @throws \Exception
     *
     * @return AbstractResource
     */
    public function getFollower($id)
    {
        if (!is_int($id)) {
            throw \Exception("Follower id is not an integer");
        }

        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/followers/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Comments made by User.
     *
     * @return CommentCollection
     */
    public function getComments()
    {

        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/comments';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Tracks favorited by User.
     *
     * @return TrackCollection
     */
    public function getFavorites()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/favorites';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Track favorited by User.
     *
     * @param int $id of the track favorited by User.
     * @throws \Exception
     *
     * @return AbstractResource
     */
    public function getFavorite($id)
    {
        if (!is_int($id)) {
            throw \Exception("Favorite id is not an integer");
        }

        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/favorites/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Groups that User belongs to.
     *
     * @return AbstractResource
     */
    public function getGroups()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/groups';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Get Web Profiles of User.
     *
     * @return AbstractResource
     */
    public function getWebProfiles()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $uri .= '/web-profiles';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    /**
     * Refresh Object State, by requesting Soundcloud.
     *
     * @return AbstractResource
     */
    public function refresh()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return $this->unserialize($serialized);
    }

    public function save()
    {
        if (!$this->isNew()) {
            throw new \LogicException("Resource is not new.");
        }

    }

    public function update($refreshState = true)
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }

        $uri = $this->get('uri');
        $this->sc->put($uri, $this->serialize());
        $response = $this->sc->send();

        if ($refreshState) {
            $this->unserialize($response->bodyRaw());
        }
   }

    public function delete()
    {
        if ($this->isNew()) {
            throw new \LogicException("Resource is new.");
        }
    }
}
