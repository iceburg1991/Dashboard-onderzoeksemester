<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 15-05-13
 * Time: 15:22
 * To change this template use File | Settings | File Templates.
 */

// Turn on all error reporting
error_reporting(-1);

// Config file
require_once dirname(__FILE__) . '/includes_cron.php';

// 30 day time filter
$from = date('Y-m-d', time() - 24 * 60 * 60);
$to = date('Y-m-d');
$now = date('Y-m-d H:i:s');

// Fetches all the Revenue metrics
$TransactionRevenueMetrics = new TransactionRevenueMetrics($service, $settings->google_analytics_profile_id, $from, $to);

// Fetches the orders per channel
$OrderPerMarketingChannel = new OrderPerMarketingChannel($service, $settings->google_analytics_profile_id, $from, $to);
$orderData = $OrderPerMarketingChannel->getOrdersPerChannel(); // all the order data

//echo "<pre>";
//print_r($orderData);
//print_r($OrderPerMarketingChannel);
//print_r($TransactionRevenueMetrics);
//echo "</pre>";


// Loop trough the all sources
foreach ($TransactionRevenueMetrics->getRevenuePerSource() as $source) {

    $marketingchannel = R::findOne( 'marketingchannel',
                                    'name = ?',
                                    array($source['source']));

    if ($marketingchannel == null) {
        $marketingchannel = R::dispense('marketingchannel');
    }

    $marketingchannelrevenue = R::dispense('marketingchannelrevenue');
    $marketingchannelrevenue->channel_revenue = $source['transactionRevenue'];
    $marketingchannelrevenue->timestamp = $now;

    $marketingchannel->marketingchannelrevenue[] = $marketingchannelrevenue;

    if (array_key_exists($source['source'], $orderData))
    {
        foreach ($orderData[$source['source']] as $orderKey => $orderValue) {

            $magentoOrderDetails = $mClient->getSalesOrderDetails($orderKey);
            $magentoOrder = new MagentoOrder($magentoOrderDetails['order_id'], $magentoOrderDetails['base_shipping_amount']);

            foreach ($magentoOrderDetails['items'] as $mProduct) {

                $product = R::findOne(  'product',
                                        'name = ?',
                                         array($mProduct['name']));


                if ($product == null) {
                    $product = R::dispense('product');
                    $product->name = $mProduct['name'];
                    $product->sku = $mProduct['sku'];

                    $productprice = R::dispense('productprice');
                    $productprice->base_cost = $mProduct['base_cost'];
                    $productprice->price = $mProduct['price'];
                    $productprice->tax_amount = $mProduct['tax_amount'];
                    $productprice->timestamp = $now;
                    $productprice->product = $product;

                    $productquantity = R::dispense('productquantity');
                    $productquantity->quantity = $mProduct['qty_ordered'];
                    $productquantity->timestamp = $now;
                } else {
                    $productprices = $product->with('SORT BY `timestamp` DESC LIMIT 1')->productprice;
                    foreach ($productprices as $pProductprice) {
                       $bool = false;
                       if ($pProductprice->base_cost != $mProduct['base_cost']) {
                           $basecost = $mProduct['base_cost'];
                           $bool = true;
                       } elseif ($pProductprice->price != $mProduct['price']) {
                           $price = $mProduct['price'];
                           $bool = true;
                       } elseif ($pProductprice->tax_amount != $mProduct['tax_amount']) {
                           $tax_amount = $mProduct['tax_amount'];
                           $bool = true;
                       }

                        if ($bool) {
                            $productprice = R::dispense('productprice');
                            $productprice->basecost = $basecost;
                            $productprice->price = $price;
                            $productprice->tax_amount = $tax_amount;
                            $productprice->product = $product;
                        }
                    }

                    $productquantity = R::find('productquantity', 'product_id = :product_id,
                                                                    timestamp = :timestamp',
                                                                    array(
                                                                       ":product_id" => $product->id,
                                                                        ":timestamp" => $now
                                                                    ));
                    $productquantity->product = $product;


                }




                $marketingchannel->productquantity[] = $productquantity;

            }
        }
    }
    R::store($marketingchannel);
}
