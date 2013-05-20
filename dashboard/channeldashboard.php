<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 18-05-13
 * Time: 16:45
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__) . '/Template.php';
class ChannelDashboard {

    public $productsbychannelArray;
    public $googleChart;

    public function __construct($channelId)
    {
        $this->createGoogleChart();
        $this->setProductsByChannelArray($channelId);
    }

    private function setProductsByChannelArray($channelId)
    {
        $from = date('Y-m-d', time() - 7 * 24 * 60 * 60);
        $to = date('Y-m-d H:00:00', time() + 2 * 60 * 60); // +2 hours because of the server time.

        // Data
        $rows = R::getAll(
            "SELECT
            p.id,
            p.name AS name,
            pr.price AS price,
            pr.base_cost AS base_cost,
            pr.tax_amount AS tax_amount,
            pq.quantity AS quantity,
            SUM(pr.price * pq.quantity) AS revenue,
            SUM((pr.base_cost + pr.tax_amount) * pq.quantity) AS costs,
            SUM((pr.price - pr.base_cost - pr.tax_amount) * pq.quantity) AS profit
            FROM product p, productquantity pq, marketingchannel mc, productprice pr
            WHERE pq.marketingchannel_id = " . $channelId . "
            AND p.id = pq.product_id
            AND pr.product_id = pq.product_id
            GROUP BY p.name
            ");

        // Extract data
        $results = R::convertToBeans('marketingchannelrevenue', $rows);

        foreach ($results as $result) {
            $this->productsbychannelArray[] = $result;
        }

        return;
    }

    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }
}