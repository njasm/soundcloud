<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Factory\ApiResponseFactory;

class User extends AbstractResource
{
    protected $resource = 'user';
    protected $writableProperties = [
        'permalink', 'username', 'country', 'city', 'first_name', 'last_name', 'description'
    ];

    /*****************
     * SUB RESOURCES *
     *****************/

    public function tracks()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/tracks';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function playlists()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/playlists';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function followings()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/followings';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function following($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/followings/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Followers of User.
     *
     * @return Collection
     */
    public function followers()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/followers';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get follower of User by id.
     *
     * @param int $id the follower id
     * @throws \Exception
     *
     * @return AbstractResource
     */
    public function follower($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/followers/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Comments made by User.
     *
     * @return CommentCollection
     */
    public function comments()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/comments';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Tracks favorited by User.
     *
     * @return TrackCollection
     */
    public function favorites()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/favorites';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Track favorited by User.
     *
     * @param int $id of the track favorited by User.
     * @throws \Exception
     *
     * @return AbstractResource
     */
    public function favorite($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/favorites/' . (string) $id;
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Groups that User belongs to.
     *
     * @return AbstractResource
     */
    public function groups()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/groups';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Get Web Profiles of User.
     *
     * @return AbstractResource
     */
    public function webProfiles()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/web-profiles';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    /**
     * Refresh Object State, by requesting Soundcloud.
     *
     * @param bool $returnNew
     * @return AbstractResource|void
     */
    public function refresh($returnNew = false)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $response = $this->sc->get($uri)->send();

        if ($returnNew) {
            return ApiResponseFactory::unserialize($response->bodyRaw());
        }

        $this->unserialize($response->bodyRaw());
    }

    public function save()
    {
        throw new \Exception("Are you creating a user with another user credentials?! humm..");
    }

    public function update($returnNew = true)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $response = $this->sc->put($uri, $this->serialize())->send();

        if ($returnNew) {
            return ApiResponseFactory::unserialize($response->bodyRaw());
        }

        $this->unserialize($response->bodyRaw());
   }

    public function delete()
    {
        throw new \Exception("Does Soundcloud allow you to delete the user via api?");
    }
}
