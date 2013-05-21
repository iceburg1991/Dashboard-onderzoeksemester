<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 18-05-13
 * Time: 16:45
 * To change this template use File | Settings | File Templates.
 */

// Turn on all error reporting
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/Template.php';
require_once dirname(__FILE__) . '/../classes/Calculator.class.php';
require_once dirname(__FILE__) . '/googleChart_init.php';

class ChannelDashboard {

    public $productsbychannelArray;
    public $googleChart;
    public $calculator;
    public $totalRevenue;
    public $soldProducts;

    private $channelId;

    private $from;
    private $to;

    public function __construct($channelId, $scope)
    {
        $this->channelId = $channelId;

        $this->from = date('Y-m-d', time() - $scope * 24 * 60 * 60);
        $this->to = date('Y-m-d H:i:s', time());

        $this->calculator = new Calculator();

        $this->setProductsByChannelArray();
        $this->getTotalRevenue();
        $this->getNumberSoldProducts();
        $this->createGoogleChart();
    }

    private function setProductsByChannelArray()
    {

        $q =
            "SELECT
            p.id,
            p.name AS name,
            pr.price AS price,
            pr.base_cost AS base_cost,
            pr.base_shipping_amount as base_shipping_amount,
            pr.tax_amount AS tax_amount,
            pq.quantity AS quantity,
            SUM(DISTINCT(pr.price * pq.quantity)) AS revenue,
            SUM(DISTINCT(pr.base_cost + pr.tax_amount + pr.base_shipping_amount) * pq.quantity) AS costs,
            SUM(DISTINCT(pr.price - pr.base_cost - pr.tax_amount - pr.base_shipping_amount) * pq.quantity) AS profit

            FROM product p, productquantity pq, marketingchannel mc, productprice pr
            WHERE pq.marketingchannel_id = " . $this->channelId . "
            AND p.id = pq.product_id
            AND pr.product_id = pq.product_id
            AND pq.timestamp >= \"" . $this->from . "\"
            AND pq.timestamp <= \"" . $this->to . "\"

            GROUP BY name";

        // Debug
        //echo $q;

        // Data
        $rows = R::getAll($q);

        // Extract data
        $beans = R::convertToBeans('product', $rows);

        foreach ($beans as $bean) {
            $this->productsbychannelArray[] = $bean;
        }
    }


    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }

    private function getTotalRevenue()
    {
        $q =
             "  SELECT
                pr.id,
                SUM(pr.price * pq.quantity) AS revenue
                FROM productprice pr, productquantity pq
                WHERE pr.product_id = pq.product_id
                AND pq.timestamp >= \"" . $this->from . "\"
                AND pq.timestamp <= \"" . $this->to .  "\"
                AND pq.marketingchannel_id = " . $this->channelId;

        // Debug
        //echo $q;

        $rows = R::getAll($q);

        // Extract data
        $results = R::convertToBeans('marketingchannelrevenue', $rows);

        foreach ($results as $result) {
            $this->totalRevenue = $result->revenue;
        }

        return;
    }

    private function getNumberSoldProducts()
    {
        $q =
            "   SELECT
                id,
                SUM( quantity ) AS quantity
                FROM productquantity
                WHERE timestamp >= \"" . $this->from . "\"
                AND timestamp <= \"" . $this->to .  "\"
                AND marketingchannel_id = " . $this->channelId;

        // Debug
        //echo $q;

        $rows = R::getAll($q);

        // Extract data
        $results = R::convertToBeans('productquantity', $rows);

        foreach ($results as $result) {
            $this->soldProducts = $result->quantity;
        }

        return;
    }
}