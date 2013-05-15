<?php
/**
 * describes an Google Analytics Account
 */
class GoogleAnalyticsAccount
{
    /**
     * containing Google Analytics Account Id
     * @var int
     */
    private $_accountId;

    /**
     * Containing name
     * @var string
     */
    private $_name;

    /**
     * Containing attached properties
     * @var array
     */
    private $_properties = array();

    /**
     * Default constructor
     */
    public function __constructor($accountId)
    {
        $this->_accountId = $accountId;
    }

    /**
     * Sets the accountId
     * @param accountId
     */
    public function setAccountId($accountId)
    {
        $this->_accountId = $accountId;
    }

    /**
     * Gets acountId
     */
    public function getAccountId()
    {
        return $this->_accountId;
    }

    /**
     * Sets name
     * @param name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Sets name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets Properties
     * @param array
     */
    public function setProperties(array $properties)
    {
        $this->_properties = $properties;
    }


    /**
     * Gets Properties
     */
    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * Adds Property object to array
     * @param Property
     */
    public function addProperty(Property $webproperty)
    {
        $this->_properties['' . $webproperty->getWebPropertyId() . ''] = $webproperty;
    }

    /**
     * Returns a Property object by its Id.
     * @param $webpropertyId
     * @return mixed
     */
    public function getPropertyById($webpropertyId)
    {
        return $this->_properties[$webpropertyId];
    }
}

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
/**
 * Gets all the GoogleAnalytics accounts, properties en profiles linked to the Google account
 */
class GoogleAnalyticsAccountSelector
{

    /**
     * Used for sending request from Google Analytics and recieving data from Google Analytics
     * @var Google_AnalyticsService
     */
    private $_service;

    /**
     * Containing Google Analytics Accounts, properties and profiles
     * @var array
     */
    private $_GoogleAnalyticsAccoounts = array();

    /**
     * Constructor
     */
    public function __construct(Google_AnalyticsService $service)
    {
        $this->_service = $service;
    }

    /**
     * Gets the GoogleAnalyticsAccounts array
     */
    public function hasGoogleAnalyticsAccounts()
    {
        return (sizeof($this->getGoogleAnalyticsAccounts()) > 0);
    }

    /**
     * Detirmens if there are GoogleAnalyticsAccounts
     */
    public function getGoogleAnalyticsAccounts()
    {
        return $this->_GoogleAnalyticsAccoounts;
    }

    /**
     * Returns a list of profiles attached to an account/property
     * When used ~all, ~all this will return a list with all profiles/accounts/proprties available to the Google account
     */
    public function listAllProfiles()
    {
        return $this->listProfiles("~all", "~all");
    }

    /**
     * Lists the profiles linked to an account/property
     */
    public function listProfiles($propertyId, $accountId)
    {
        $list = $this->listManagementProfiles($propertyId, $accountId);
        $this->_GoogleAnalyticsAccoounts = array();

        if (sizeof($list['items']) > 0) {
            foreach ($list['items'] as $item) {
                // GoogleAnalyticsAccount
                $accountId = $item['accountId'];
                $accountName = $item['websiteUrl'];

                // Property
                $propertyName = $item['name'];
                $webPropertyId = $item['webPropertyId'];

                // Profile
                $profileId = $item['id'];
                $profileName = $item['name'];

                // Profile always gets created
                $profile = new Profile();
                $profile->setProfileId($profileId);
                $profile->setName($profileName);

                // If the account doesn't exists, create it and add the property and the profile right away
                if (!array_key_exists($accountId, $this->_GoogleAnalyticsAccoounts)) {
                    $property = new Property();
                    $property->setWebPropertyId($webPropertyId);
                    $property->setName($propertyName);
                    $property->addProfile($profile);

                    $GoogleAnalyticsAccount = new GoogleAnalyticsAccount();
                    $GoogleAnalyticsAccount->setAccountId($accountId);
                    $GoogleAnalyticsAccount->addProperty($property);
                    $GoogleAnalyticsAccount->setName($accountName);

                    $this->_GoogleAnalyticsAccoounts['' . $accountId . ''] = $GoogleAnalyticsAccount;
                } // Check if the property excists in the specified account array.
                else if (!array_key_exists($webPropertyId, $this->_GoogleAnalyticsAccoounts[$accountId]->getProperties())) {
                    // The property doesn't exists, lets create it and add the profile to it.
                    $property = new Property();
                    $property->setWebPropertyId($webPropertyId);
                    $property->setName($propertyName);
                    $property->addProfile($profile);

                    // And add it to the Google Analytics account.
                    $this->_GoogleAnalyticsAccoounts[$accountId]->addProperty($property);
                } else {
                    // Property exists, lets add the profile to it
                    $properties = $this->_GoogleAnalyticsAccoounts[$accountId]->getProperties();
                    $property = $properties[$webPropertyId];
                    $property->addProfile($profile);
                }
            }
        }
        return $this->_GoogleAnalyticsAccoounts;
    }

    /**
     *    Lists all the accounts/properties and profiles
     */
    private function listManagementProfiles($propertyId, $accountId)
    {
        return $this->_service->management_profiles->listManagementProfiles($accountId, $propertyId);
    }
}

?>