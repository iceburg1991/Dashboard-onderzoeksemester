<?php

/**
 * Class Property
 */
class Property
{
    /**
     * containing webpropertyId
     * @var int
     */
    private $_webPropertyId;

    /**
     * containing name
     * @var string
     */
    private $_name;

    /**
     * containing all child Profile objects
     * @var array
     */
    private $_profiles = array();


    /**
     * @param $webPropertyId
     */
    public function setWebPropertyId($webPropertyId)
    {
        $this->_webPropertyId = $webPropertyId;
    }

    /**
     * @return mixed
     */
    public function getWebPropertyId()
    {
        return $this->_webPropertyId;
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
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param array $profiles
     */
    public function setProfiles(array $profiles)
    {
        $this->_profiles = $profiles;
    }

    /**
     * @return array
     */
    public function getProfiles()
    {
        return $this->_profiles;
    }

    /**
     * @param Profile $profile
     */
    public function addProfile(Profile $profile)
    {
        $this->_profiles['' . $profile->getProfileId() . ''] = $profile;
    }

    /**
     * Returns a profile by its Id
     * @param $profileId
     * @return mixed
     */
    public function getProfileById($profileId)
    {
        return $this->_profiles[$profileId];
    }
}

?>