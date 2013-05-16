<?php
/**
 * Class TransactionRevenueMetrics
 */
class TransactionRevenueMetrics extends GoogleAnalyticsMetricsParser
{
    /**
     * used to store totalRevenue
     * @var float
     */
    private $_totalRevenue;

    /**
     * used to store results per channel
     * @var array
     */
    private $_revenuePerSource = array();

    /**
     * @param                         $service
     * @param                         $profileId
     * @param Google_AnalyticsService $from
     * @param                         $to
     */
    public function __construct(Google_AnalyticsService $service, $profileId, $from, $to)
    {
        // dimensions
        $dimensions = 'ga:source,ga:medium';
        $this->_params[] = 'source';
        $this->_params[] = 'medium';

        // metrics
        $metrics = 'ga:visits,ga:transactionRevenue,ga:transactions,ga:uniquePurchases,ga:transactionShipping,ga:transactionTax';
        $this->_params[] = 'visits';
        $this->_params[] = 'transactionRevenue';
        $this->_params[] = 'transactions';
        $this->_params[] = 'uniquePurchases';
        $this->_params[] = 'transactionShipping';
        $this->_params[] = 'transactionTax';

        parent::__construct($metrics, $dimensions, $service, $profileId, $from, $to);
        $this->parse();
    }

    /**
     * Sets the data retrieved from Google Analytics in local vars
     */
    public function parse()
    {
        parent::parse();

        $this->_totalRevenue = $this->_data['totalsForAllResults']['ga:transactionRevenue'];
        $this->_revenuePerSource = $this->_results;
    }

    /**
     * @return mixed
     */
    public function getTotalRevenue()
    {
        return $this->_totalRevenue;
    }

    /**
     * @return array
     */
    public function getRevenuePerSource()
    {
        return $this->_revenuePerSource;
    }
}

?>