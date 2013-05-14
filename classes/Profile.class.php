<?php

/**
 * Class Profile
 */
class Profile
{
    /**
     * containing profileId
     * @var int
     */
    private $_profileId;

    /**
     * containing profile name
     * @var string
     */
    private $_name;

    /**
     * containing profile website url
     * @var string
     */
    private $_websiteUrl;

    /**
     * @return mixed
     */
    public function getProfileId()
    {
        return $this->_profileId;
    }

    /**
     * @param $profileId
     */
    public function setProfileId($profileId)
    {
        $this->_profileId = $profileId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }


    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getWebsiteUrl()
    {
        return $this->_websiteUrl;
    }

    /**
     * @param $websiteUrl
     */
    public function setWebsiteUrl($websiteUrl)
    {
        $this->_websiteUrl = $websiteUrl;
    }
}

?>