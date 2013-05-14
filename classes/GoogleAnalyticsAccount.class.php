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

?>