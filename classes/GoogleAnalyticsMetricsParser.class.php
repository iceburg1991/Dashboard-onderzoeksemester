<?php
/**
 * Parses an metric class and returns the fetched data from Google in an array
 */
class GoogleAnalyticsMetricsParser
{
    /**
     * containing a string array with metrics
     * https://developers.google.com/analytics/devguides/reporting/core/dimsmets
     * @var string[]
     */
    private $_metrics;

    /**
     * containing a string array wit dimensions
     * @var string[]
     */
    private $_dimensions;

    /**
     * containg the parameters used for the dimensions and metrics
     * @var array
     */
    public $_params = array();

    /**
     * used for requesting data
     * @var Google_AnalyticsService
     */
    private $_service;

    /**
     * containing the Google Analytics profile to use for requesting data
     * @var int
     */
    private $_profileId;

    /**
     * used as starting point for date scope
     * @var date
     */
    private $_from;

    /**
     * used as end point for date scope
     * @var date
     */
    private $_to;

    /**
     * contains all raw data fetched from Google Analytics
     * @var array
     */
    public $_data = array();

    /**
     * contains all processed data
     * @var array
     */
    public $_results = array();

    /**
     * Constructor
     *
     * @param $metrics
     * @param $dimensions
     * @param $service
     * @param $profileId
     * @param $from
     * @param $to
     */
    public function __construct($metrics, $dimensions, Google_AnalyticsService $service, $profileId, $from, $to)
    {
        $this->_metrics = $metrics;
        $this->_dimensions = $dimensions;
        $this->_service = $service;
        $this->_profileId = $profileId;
        $this->_from = $from;
        $this->_to = $to;
    }

    /**
     * Parses the metrics and dimensions and sends these to Google Analytics
     * Processes the retrieved data
     */
    public function parse()
    {
        $this->_data = $this->_service->data_ga->get('ga:' . $this->_profileId, $this->_from, $this->_to, $this->_metrics, array('dimensions' => $this->_dimensions));
        if (isset($this->_data['rows']) && sizeof($this->_data['rows']) > 0) {
            foreach ($this->_data['rows'] as $row) {
                $set = array();
                foreach ($row as $key => $value) {
                    $set[$this->_params[$key]] = $value;
                }
                array_push($this->_results, $set);
            }
        }
    }
}

?>