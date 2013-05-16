<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 13-05-13
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */

class MagentoProduct extends RedBean_SimpleModel
{

    /**
     * @var
     * @type = int
     */
    private $_magentoId;

    /**
     * @var
     * @type = string
     */
    private $_productSku;

    /**
     * @var
     * @type = float
     */
    private $_baseCost;

    /**
     * @var
     * @type = float
     */
    private $_price;

    /**
     * @var
     * @type = float
     */
    private $_tax_amount;

    /**
     * @var
     * @type = float
     */
    private $_quantity;

    /**
     * @var
     */
    private $_name;

    /**
     * @param int    $magentoId
     * @param string $productSku
     * @param float  $baseCost
     * @param float  $price
     * @param float  $tax_amount
     * @param float  $quantity
     */
    public function __construct($magentoId, $productSku, $name, $baseCost, $price, $tax_amount, $quantity)
    {
        $this->_magentoId = $magentoId;
        $this->_productSku = $productSku;
        $this->_name = $name;
        $this->_baseCost = $baseCost;
        $this->_price = $price;
        $this->_tax_amount = $tax_amount;
        $this->_quantity = $quantity;
    }

    /**
     * @param float $baseCost
     */
    public function setBaseCost($baseCost)
    {
        $this->_baseCost = $baseCost;
    }

    /**
     * @return float
     */
    public function getBaseCost()
    {
        return $this->_baseCost;
    }

    /**
     * @param int $magentoId
     */
    public function setMagentoId($magentoId)
    {
        $this->_magentoId = $magentoId;
    }

    /**
     * @return int
     */
    public function getMagentoId()
    {
        return $this->_magentoId;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param string $productSku
     */
    public function setProductSku($productSku)
    {
        $this->_productSku = $productSku;
    }

    /**
     * @return string
     */
    public function getProductSku()
    {
        return $this->_productSku;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * @param float $tax_amount
     */
    public function setTaxAmount($tax_amount)
    {
        $this->_tax_amount = $tax_amount;
    }

    /**
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->_tax_amount;
    }

    /**
     * @return float
     */
    public function getProductProfit()
    {
        return $this->_price - $this->_baseCost;
    }

    /**
     * @param  $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return
     */
    public function getName()
    {
        return $this->_name;
    }
}