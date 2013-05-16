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

// 1 day time filter
$from = date('Y-m-d', time() - 24 * 60 * 60);
$to = date('Y-m-d');

// For timestamp
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

    $marketingchannel = R::findOne('marketingchannel',
        'name = ?',
        array($source['source']));

    if ($marketingchannel == null) {
        $marketingchannel = R::dispense('marketingchannel');
        $marketingchannel->name = $source['source'];
        $channelId = R::store($marketingchannel);
    } else {
        $channelId = $marketingchannel->getID();
    }

    $marketingchannelrevenue = R::dispense('marketingchannelrevenue');
    $marketingchannelrevenue->channel_revenue = $source['transactionRevenue'];
    $marketingchannelrevenue->timestamp = $now;
    $marketingchannelrevenue->marketingchannel_id = $channelId;
    R::store($marketingchannelrevenue);

    if (array_key_exists($source['source'], $orderData)) {
        foreach ($orderData[$source['source']] as $orderKey => $orderValue) {

            $magentoOrderDetails = $mClient->getSalesOrderDetails($orderKey);
            $magentoOrder = new MagentoOrder($magentoOrderDetails['order_id'], $magentoOrderDetails['base_shipping_amount']);

            foreach ($magentoOrderDetails['items'] as $mProduct) {

                if ($mProduct['base_cost'] > 0 && $mProduct['price'] > 0) {

                    $product = R::findOne('product',
                        'sku = ?',
                        array($mProduct['sku']));

                    if ($product == null) {
                        $product = R::dispense('product');
                        $product->name = $mProduct['name'];
                        $product->sku = $mProduct['sku'];
                        $productId = R::store($product);

                        $productprice = R::dispense('productprice');
                        $productprice->product_id = $productId;
                        $productprice->base_cost = $mProduct['base_cost'];
                        $productprice->price = $mProduct['price'];
                        $productprice->tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                        $productprice->timestamp = $now;
                        R::store($productprice);;

                        $productquantity = R::dispense('productquantity');
                        $productquantity->product_id = $productId;
                        $productquantity->marketingchannel_id = $channelId;
                        $productquantity->quantity = $mProduct['qty_ordered'];
                        $productquantity->timestamp = $now;
                        R::store($productquantity);
                    } else {
                        $productId = $product->id;

                        $productprices = R::find('productprice',
                            ' product_id = :product_id ORDER BY :sort DESC LIMIT 1',
                            array(
                                ':product_id' => $productId,
                                ':sort'       => 'timestamp'
                            ));

                        foreach ($productprices as $pProductprice) {

                            $basecost = $pProductprice->base_cost;
                            $price = $pProductprice->price;
                            $tax_amount = $pProductprice->tax_amount;

                            $bool = false;
                            if ($basecost != round($mProduct['base_cost'], 2)) {
                                $basecost = $mProduct['base_cost'];
                                $bool = true;
                            } elseif ($price != round($mProduct['price'],2)) {
                                $price = $mProduct['price'];
                                $bool = true;
                            } elseif ($tax_amount != round(($mProduct['tax_amount'] / $mProduct['qty_ordered']), 2)) {
                                $tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                                $bool = true;
                            }

                            if ($bool) {
                                $productprice = R::dispense('productprice');
                                $productprice->product_id = $productId;
                                $productprice->base_cost = $basecost;
                                $productprice->price = $price;
                                $productprice->tax_amount = $tax_amount;
                                $productprice->timestamp = $now;
                                R::store($productprice);
                            }

                            $productquantities = R::find('productquantity', 'product_id = :product_id AND timestamp = :timestamp AND marketingchannel_id = :channel_id',
                                array(
                                    ":product_id" => $productId,
                                    ":timestamp"  => $now,
                                    ":channel_id" => $channelId
                                ));

                            foreach ($productquantities as $productquantity) {
                                $productquantity->quantity += $mProduct['qty_ordered'];
                                R::store($productquantity, $productquantity->id);
                            }
                        }
                    }
                }
            }
        }
    }
}
