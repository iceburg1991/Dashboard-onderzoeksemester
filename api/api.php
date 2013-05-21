<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dylan
 * Date: 21-5-13
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

// RedBean PHP
require('../RedbeanPHPLib/rb.php');
R::setup('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', '' . DB_USERNAME . '', '' . DB_PASS . '');

// Settings
$settings = R::load('settings', 1);

if (isset($_POST) && $_POST['action'] == 'get') {
    $scope = $_POST['scope'];
    $from = date('Y-m-d', time() - $scope * 24 * 60 * 60);
    $to = date('Y-m-d H:i:s', time());

    switch ($_POST['methode']) {
        case 'marketingChannelRevenue':
            $this->toJsonString(getMarketingChannelRev($from,$to));
            break;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'test') {
    $scope = $_COOKIE['scope'];
    $from = date('Y-m-d', time() - $scope * 24 * 60 * 60);
    $to = date('Y-m-d H:i:s', time());
    $this->toJsonString(getMarketingChannelRev($from,$to));
}

function toJsonString($object)
{
    //header("Content-type: application/json");
    echo json_encode($object);
}

function getMarketingChannelRev($from, $to)
{
    $q = "SELECT
                mc.id AS id,
                mc.name AS name,
                SUM(mcr.channel_revenue) AS revenue
                FROM marketingchannel mc, marketingchannelrevenue mcr
                WHERE mcr.timestamp >= \"" . $from . "\"
                AND mcr.timestamp <= \"" . $to . "\"
                AND mc.id = mcr.marketingchannel_id
                GROUP BY mc.name";


    // Data
    $rows = R::getAll($q);

    // Extract data
    $channels = R::convertToBeans('marketingchannelrevenue', $rows);

    $tempArray = array();

    // Add to this array
    foreach ($channels as $channel) {
        $object = array(
            $channel->name => $channel->revenue
        );
        array_push($tempArray, $object);
    }
    
    return $tempArray;
}