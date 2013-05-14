<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 13-05-13
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

class MagentoOrder
{

    /**
     * @var
     */
    private $_order_id;

    /**
     * @var
     */
    private $_shipping_cost;

    /**
     * @var array
     */
    private $_products = array();

    /**
     * @param array $products
     * @param       $order_id
     * @param       $shipping_costs
     */
    public function __construct($order_id, $shipping_costs)
    {
        $this->_order_id = $order_id;
        $this->_shipping_cost = $shipping_costs;
    }

    /**
     * @param $order_id
     */
    public function setOrderId($order_id)
    {
        $this->_order_id = $order_id;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->_order_id;
    }

    /**
     * @param $products
     */
    public function setProducts($products)
    {
        $this->_products = $products;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }

    /**
     * @param $shipping_cost
     */
    public function setShippingCost($shipping_cost)
    {
        $this->_shipping_cost = $shipping_cost;
    }

    /**
     * @return mixed
     */
    public function getShippingCost()
    {
        return $this->_shipping_cost;
    }

    /**
     * @param MagentoProduct $product
     */
    public function addProduct(MagentoProduct $product)
    {
        array_push($this->_products, $product);
    }
}