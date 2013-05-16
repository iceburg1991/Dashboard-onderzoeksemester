<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 05-05-13
 * Time: 17:01
 * To change this template use File | Settings | File Templates.
 */

class OrderPerMarketingChannel extends GoogleAnalyticsMetricsParser
{
    /**
     * Constructor, passes on the service variable to the parser
     *
     * @param Google_AnalyticsService $service
     * @param                         $profileId
     * @param Google_AnalyticsService $from
     * @param                         $to
     */
    public function __construct(Google_AnalyticsService $service, $profileId, $from, $to)
    {
        // dimensions
        $dimensions = 'ga:source,ga:transactionId,ga:productSku';
        $this->_params[] = 'source';
        $this->_params[] = 'transactionId';
        $this->_params[] = 'productSku';

        // metrics
        $metrics = "ga:itemQuantity,ga:itemRevenue";
        //$metrics = 'ga:transactionRevenue,ga:transactionShipping,ga:transactionTax';
        $this->_params[] = 'itemQuantity';
        $this->_params[] = 'itemRevenue';
        //->_params[] = 'transactionTax';

        parent::__construct($metrics, $dimensions, $service, $profileId, $from, $to);
        $this->parse();
    }

    /**
     * Parses the metrics and dimmensions
     */
    public function parse()
    {
        parent::parse();
    }

    /**
     * Sorts the array by setting the orders per channel
     */
    public function getOrdersPerChannel()
    {
        $result = array();
        $source = null;
        $transactionId = null;

        foreach ($this->_results as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'source') {
                    $source = $value;
                    if (!isset($result[$source])) {
                        $result[$source] = array();
                    }
                } elseif ($key == 'transactionId') {
                    $transactionId = $value;
                    if (!isset($result[$source][$transactionId])) {
                        $result[$source][$transactionId] = array();
                    }
                } elseif ($key == 'productSku') {
                    $productSku = $value;
                    if (!isset($result[$source][$transactionId][$productSku])) {
                        $result[$source][$transactionId][$productSku] = array();
                    }
                    $result[$source][$transactionId][$productSku]['productSku'] = $value;
                } elseif ($key == 'itemRevenue') {
                    $result[$source][$transactionId][$productSku]['itemRevenue'] = $value;
                } else {
                    $result[$source][$transactionId][$productSku]['itemQuantity'] = $value;
                }
            }
        }

        return $result;
    }
}
