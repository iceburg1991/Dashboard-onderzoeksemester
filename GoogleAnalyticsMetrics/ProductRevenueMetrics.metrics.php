<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 02-05-13
 * Time: 18:11
 * To change this template use File | Settings | File Templates.
 */

class ProductRevenueMetrics extends GoogleAnalyticsMetricsParser
{
    /**
     * Contains productSKU
     * @var string
     */
    private $_productSKU;

    /**
     * Contains productname
     * @var string
     */
    private $_productName;

    /**
     * Contains product revenue
     * @var float
     */
    private $_productRevenue;

    /**
     * Constructor, passes on the service variable to the parser
     * @param Google_AnalyticsService $service
     * @param $profileId
     * @param Google_AnalyticsService $from
     * @param $to
     */
    function __construct(Google_AnalyticsService $service, $profileId, $from, $to)
    {
        // dimensions
        $dimensions = 'ga:source';
        $this->_params[] = 'source';

        // metrics
        $metrics = 'ga:productSku,ga';
        $this->_params[] = 'productSku';

        parent::__construct($metrics, $dimensions, $service, $profileId, $from, $to);
        $this->parse();
    }

    /**
     * Sets the data retrieved from Google Analytics in local vars
     */
    function parse()
    {
        parent::parse();

        //$this->_totalRevenue = $this->_data['totalsForAllResults']['ga:transactionRevenue'];
        //$this->_revenuePerSource = $this->_results;

        echo "<pre>";
        print_r($this->_data);
        echo "</pre>";
    }
}