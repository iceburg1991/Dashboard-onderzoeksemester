<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 03-05-13
 * Time: 20:07
 * To change this template use File | Settings | File Templates.
 */

class MagentoClient
{
    /**
     * Containing a soapclient
     * @var SoapClient
     */
    private $_client;

    /**
     * Containing the session
     * @var
     */
    private $_session;

    /**
     * Containing api user
     * @var
     */
    private $_apiUser;

    /**
     * Containing api key
     * @var
     */
    private $_apiKey;

    /**
     * Contains the Magento webshop hostname and which API to use
     * @var
     */
    private $_magentoHost;

    /**
     * Sets the vars
     */
    public function __construct($apiUser, $apiKey, $host)
    {

        $this->_apiUser = $apiUser;
        $this->_apiKey = $apiKey;
        $this->_magentoHost = $host;

        $this->connect();
    }

    /**
     * Connects to the Magento API and sets the session
     */
    public function connect()
    {
        $this->_client = new SoapClient($this->_magentoHost);
        $this->_session = $this->_client->login($this->_apiUser, $this->_apiKey);
    }

    /**
     * Closses the active session
     */
    public function close()
    {
        $this->_client->endSession($this->_session);
    }

    /**
     * Executes a request on the Magento API and gets
     *
     * @param       $method
     * @param mixed $arg -> could be an single argument or an array with arguments
     *
     * @return mixed
     */
    private function _call($method, $arg)
    {
        if (isset($arg) && !strlen($arg) > 0) {
            return $this->_client->call($this->_session, $method);
        }
        return $this->_client->call($this->_session, $method, $arg);
    }

    /**
     * Gets the product info by its Sku
     *
     * @param $sku
     *
     * @return mixed
     */
    public function getProductInfo($sku)
    {
        //return $this->_call('catalog_product.info', $sku);
        try {
            return $this->_call('catalog_product.info', $sku);
        } catch (SoapFaultException $e) {
        }
        catch (Exception $e) {
        }
    }

    /**
     * Returns a list with all products in Magento
     * @return mixed
     */
    public function getProductList()
    {
        return $this->_call('catalog_product.list', '');
    }

    /**
     * Calculates the profit made on a product by substracting the product costs from the product price
     *
     * @param $sku
     *
     * @return mixed
     */
    public function getProductProfit($sku)
    {
        $product = $this->getProductInfo((string)$sku);
        return $product['price'] - $product['cost'];
    }

    /**
     * Returns complete sales order by its Id
     *
     * @param $salesOrderId
     *
     * @return mixed
     */
    public function getSalesOrderDetails($salesOrderId)
    {
        return $this->_call('sales_order.info', $salesOrderId);
    }

    /**
     * Returns the items from a sales order by its Id
     *
     * @param $salesOrderId
     *
     * @return mixed
     */
    public function getSalesOrderDetailsItems($salesOrderId)
    {
        $data = $this->getSalesOrderDetails($salesOrderId);
        return $data['items'];
    }

    /**
     * Returns all the Sku's on a sales order by its Id
     *
     * @param $salesOrderId
     *
     * @return array
     */
    public function getSalesOrderDetailsItemsSku($salesOrderId)
    {
        $skus = array();
        $items = $this->getSalesOrderDetailsItems($salesOrderId);
        foreach ($items as $item) {
            array_push($skus, $item['sku']);
        }
        return $skus;
    }

    /**
     * Returns the profit made on a sales order by calculating the profit made per product by its Id
     *
     * @param $salesOrderId
     *
     * @return int
     */
    public function getSalesOrderProfit($salesOrderId)
    {
        $profit = 0;
        $items = $this->getSalesOrderDetailsItems($salesOrderId);

        foreach ($items as $item) {
            if ($item['base_cost'] > 0) {
                $profit += $item['price'] - $item['base_cost'];
            }
        }
        return $profit;
    }

    /**
     * Calculates the basecosts of all items on a single order
     *
     * @param $salesOrderId
     *
     * @return int
     */
    public function getSalesOrderBaseCost($salesOrderId)
    {
        $basecost = 0;
        $items = $this->getSalesOrderDetailsItems($salesOrderId);

        foreach ($items as $item) {
            $basecost += $item['base_cost'];
        }

        return $basecost;
    }
}

?>