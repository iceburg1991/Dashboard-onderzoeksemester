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

    $marketingchannel = R::findOne('marketingchannel',
        'name = ?',
        array($source['source']));

    if ($marketingchannel == null) {
        $marketingchannel = R::dispense('marketingchannel');
        $marketingchannel->name = $source['source'];
    }

    $marketingchannelrevenue = R::dispense('marketingchannelrevenue');
    $marketingchannelrevenue->channel_revenue = $source['transactionRevenue'];
    $marketingchannelrevenue->timestamp = $now;
    $marketingchannel->ownMarketingchannelrevenue = array($marketingchannelrevenue);

    if (array_key_exists($source['source'], $orderData))
    {
        foreach ($orderData[$source['source']] as $orderKey => $orderValue)
        {
            $magentoOrderDetails = $mClient->getSalesOrderDetails($orderKey);
            $magentoOrder = new MagentoOrder($magentoOrderDetails['order_id'], $magentoOrderDetails['base_shipping_amount']);

            foreach ($magentoOrderDetails['items'] as $mProduct) {

                // SKU is more accurate
                $product = R::findOne('product',
                    'sku = ?',
                    array($mProduct['sku']));

                if ($product == null) {
                    // When the product doesn't excists create the object
                    $product = R::dispense('product');
                    $product->name = $mProduct['name'];
                    $product->sku = $mProduct['sku'];

                    // Set its prices, because those are unknow to
                    $productprice = R::dispense('productprice');
                    $productprice->base_cost = $mProduct['base_cost'];
                    $productprice->price = $mProduct['price'];
                    $productprice->tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                    $productprice->timestamp = $now;

                    // The quantity is unknown, create that!
                    $productquantity = R::dispense('productquantity');
                    $productquantity->quantity = $mProduct['qty_ordered'];
                    $productquantity->timestamp = $now;

                    // Create a link to product
                    $product->ownProductprice[] = $productprice;
                    $product->ownProductquantity[] = $productquantity;
                    R::store($product); // store that bitch!

                    // First time we have to add this quantity
                    $marketingchannel->ownProductquantity[] = $productquantity;

                } else {

                    // When the product does excists we have to compare the prices.
                    $productprices = R::find('productprice',
                        'productprice_id = :product_id ORDER BY :sort DESC LIMIT 1',
                        array(
                            ':product_id' => $product->getID(),
                            ':sort' => 'timestamp'
                        ));

                    $price = false;

                    foreach ($productprices as $pProductprice) {

                        // Should we add?
                        $price = false;

                        // The other values have to get set, otherwise the value would be 0.
                        $basecost = $productprice->base_cost;
                        $price = $productprice->price;
                        $tax_amount = $productprice->tax_amount;

                        // Compare the prices, if they dont match create a new object with the new price.
                        // When the other prices don't differ the price would be the same as the previous price.
                        if ($pProductprice->base_cost != $mProduct['base_cost']) {
                            $basecost = $mProduct['base_cost'];
                            $price = true;
                        } elseif ($pProductprice->price != $mProduct['price']) {
                            $price = $mProduct['price'];
                            $price = true;
                        } elseif ($pProductprice->tax_amount != $mProduct['tax_amount']) {
                            $tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                            $price = true;
                        }

                        // When the price did change set the variables and add the new product price to the array
                        if ($price) {
                            $productprice = R::dispense('productprice');
                            $productprice->basecost = $basecost;
                            $productprice->price = $price;
                            $productprice->tax_amount = $tax_amount;
                            $product->ownProductprice[] = $productprice;
                        }
                    }

                    // If this product got sold before trough this channel add the quantity to this product
                    $productquantity = R::findOne(
                        'productquantity',
                        'product_id = :product_id AND :timestamp = :now AND marketingchannel_id = :marketingchannel_id',
                        array(
                            ':product_id' => $product->getID(),
                            ':timestamp' => 'timestamp',
                            ':now' => $now,
                            ':marketingchannel_id' => $marketingchannel->getID()
                        ));

                    // When we find something..
                    $quantity = false;
                    if ($productquantity != null) {
                        // Add up the quantity..
                        $productquantity->quantity += $mProduct['qty_ordered'];
                        // Replace the excisting quantity object
                        $product->ownProductquantity = $productquantity;
                        $quantity = true;
                    }

                    // Only when or the quantity or the price was changed store the product
                    if ($quantity || $price) {
                        R::store($product);
                    }
                }
            }
        }
    }
    // Store this channel with all the objects related to it.
    R::store($marketingchannel);
}
