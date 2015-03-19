<?php

namespace Njasm\Soundcloud\Resource;

class User extends AbstractResource
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $permalink;
    /** @var string */
    protected $username;
    /** @var string */
    protected $lastModified;
    /** @var string */
    protected $uri;
    /** @var string */
    protected $permalink_url;
    /** @var string */
    protected $avatar_url;
    /** @var string */
    protected $country;
    /** @var string */
    protected $first_name;
    /** @var string */
    protected $last_name;
    /** @var string */
    protected $description;
    /** @var string */
    protected $city;
    /** @var string */
    protected $discogs_name;
    /** @var string */
    protected $myspace_name;
    /** @var string */
    protected $website;
    /** @var string */
    protected $website_title;
    /** @var bool */
    protected $online;
    /** @var int */
    protected $track_count;
    /** @var int */
    protected $playlist_count;
    /** @var string */
    protected $plan;
    /** @var int */
    protected $public_favorites_count;
    /** @var int */
    protected $followers_count;
    /** @var int */
    protected $followings_count;
    /** @var array */
    protected $subscriptions;

    public function getId()
    {
        return $this->id;
    }

    public function getPermaLink()
    {
        return $this->permalink;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getLastModified()
    {
        return $this->lastModified;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getPermaLinkUrl()
    {
        return $this->permalink_url;
    }

    public function getAvatarUrl()
    {
        return $this->avatar_url;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = (string) $country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = (string) $city;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($name)
    {
        $this->first_name = (string) $name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($lastName)
    {
        $this->last_name = (string) $lastName;
    }

    public function getFullName()
    {
        return implode(" ", array($this->first_name, $this->last_name));
    }

    public function setFullName($fullName)
    {
        list($first, $last) = explode(" ", (string) $fullName, 2);
        $this->setFirstName($first);
        $this->setLastName($last);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
    }
}
