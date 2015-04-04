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

    public function getTracks()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/tracks';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function getPlaylists()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/playlists';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function  getFollowings()
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $uri .= '/followings';
        $serialized = $this->sc->get($uri)->send()->bodyRaw();

        return ApiResponseFactory::unserialize($serialized);
    }

    public function getFollowing($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        if (!is_int($id)) {
            throw \Exception("Following id is not an integer");
        }

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
    public function getFollowers()
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
    public function getFollower($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        if (!is_int($id)) {
            throw \Exception("Follower id is not an integer");
        }

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
    public function getComments()
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
    public function getFavorites()
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
    public function getFavorite($id)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        if (!is_int($id)) {
            throw \Exception("Favorite id is not an integer");
        }

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
    public function getGroups()
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
    public function getWebProfiles()
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

    public function update($refreshState = true)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $uri = $this->get('uri');
        $this->sc->put($uri, $this->serialize());
        $response = $this->sc->send();

        if ($refreshState) {
            $this->unserialize($response->bodyRaw());
        }
   }

    public function delete()
    {
        throw new \Exception("Does Soundcloud allow you to delete the user via api?");
    }
}
