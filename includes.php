<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 08-05-13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */
// Config file
require_once dirname(__FILE__) . '/config.php';

// RedBean PHP
require_once dirname(__FILE__) . '/Lib/RedbeanPHPLib/rb.php';
R::setup('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', '' . DB_USERNAME . '', '' . DB_PASS . '');

// Settings
$settings = R::load('settings', 1);

if (isset($_COOKIE['scope'])) {
    $scope = $_COOKIE['scope'];
} else {
    $scope = 1;
    setcookie('scope', $scope);
}

// Sets the COST by ratio (day,     week,   month)
// ratio:         ie        30,     4       1
$time = time();
$ratio = cal_days_in_month(CAL_GREGORIAN, date('n', $time), date('Y', $time));
if ($scope == 7) {
    $ratio = 4;
} elseif ($scope == 31) {
    $ratio = 1;
}


define("COSTS", ($settings->costs / $ratio));

// Google Client and Google Analytics Service
require_once dirname(__FILE__) . '/Lib/GoogleClientLib/Google_Client.php';
require_once dirname(__FILE__) . '/Lib/GoogleClientLib/contrib/Google_AnalyticsService.php';
require_once dirname(__FILE__) . '/clients/GoogleClient.php';

// Google Account, property and profile
require_once dirname(__FILE__) . '/classes/GoogleAnalyticsAccount.class.php';

// Dimension & metrics
require_once dirname(__FILE__) . '/classes/GoogleAnalyticsMetricsParser.class.php';
require_once dirname(__FILE__) . '/GoogleAnalyticsMetrics/TransactionRevenueMetrics.metrics.php';
require_once dirname(__FILE__) . '/GoogleAnalyticsMetrics/ProductRevenueMetrics.metrics.php';
require_once dirname(__FILE__) . '/GoogleAnalyticsMetrics/OrderPerMarketingChannel.metrics.php';

// Tools
require_once dirname(__FILE__) . '/classes/Calculator.class.php';

// MagentoClient
require_once dirname(__FILE__) . '/Lib/MagentoClientLib/MagentoClient.php';
require_once dirname(__FILE__) . '/clients/MagentoClient.php';
require_once dirname(__FILE__) . '/classes/MagentoProduct.class.php';
require_once dirname(__FILE__) . '/classes/MagentoOrder.class.php';
?>