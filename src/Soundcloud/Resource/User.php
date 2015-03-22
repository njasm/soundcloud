<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Factory\AbstractFactory;

class User extends AbstractResource
{
    protected $writableProperties = [
        'permalink', 'username', 'country', 'city', 'first_name', 'last_name', 'description'
    ];

    /*****************
     * SUB RESOURCES *
     *****************/

    public function getTracks()
    {
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/tracks';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getPlaylists()
    {
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/playlists';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function  getFollowings()
    {
        // return UserCollection
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/followings';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFollowing($id)
    {
        if (!is_int($id)) {
            throw \Exception("Following id is not an integer");
        }

        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/followings/' . (string) $id;
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFollowers()
    {
        // return UserCollection
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/followers';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFollower($id)
    {
        if (!is_int($id)) {
            throw \Exception("Follower id is not an integer");
        }

        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/followers/' . (string) $id;
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getComments()
    {
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/comments';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFavorites()
    {
        // return TrackCollection favorited by User
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/favorites';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getFavorite($id)
    {
        if (!is_int($id)) {
            throw \Exception("Favorite id is not an integer");
        }

        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/favorites/' . (string) $id;
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getGroups()
    {
        // return GroupCollection joined by User
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/groups';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function getWebProfiles()
    {
        // return Web-Profiles of User
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $uri .= '/web-profiles';
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return AbstractFactory::unserialize($serialized);
    }

    public function refresh()
    {
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        $serialized = $this->sc->get($uri)->request()->bodyRaw();

        return $this->unserialize($serialized);
    }

    public function save()
    {

    }

    public function update()
    {
        $uri = $this->get('uri');
        $uri = str_replace('https://api.soundcloud.com', '', $uri);
        /*
        $serialized = $this->serialize();

        $allowedData["user[permalink]"] = "hybrid-species"; //$serialized["user[permalink]"];
        $allowedData["user[username]"] = "HybridSpecies"; //$serialized["user[permalink]"];
        $allowedData["user[first_name]"] = "Nelson"; //$serialized["user[first_name]"];
        $allowedData["user[last_name]"] = "J Morais"; //$serialized["user[last_name]"];
        $allowedData["user[country]"] = "Portugal"; //$serialized["user[permalink]"];
        $allowedData["user[city]"] = "Lisboa"; //$serialized["user[permalink]"];
        $allowedData["user[description]"] = stripos($serialized["user[description]"], '~') !== false
            ? str_replace("~", "", $serialized["user[description]"])
            : $serialized["user[description]"] . "~";
        */
        $this->sc->put($uri, $this->serialize());
        $this->sc->request();
   }

    public function delete()
    {

    }
}
