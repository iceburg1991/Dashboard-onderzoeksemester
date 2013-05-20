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

// 24 hour time filter
$from = date('Y-m-d', time() - 24 * 60 * 60);
$to = date('Y-m-d');

// Used to define now timestamp.
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
    // We only have to check the channel when this channel actually produced any orders. If we can't find any orders we cannot calculate anything.
    if (array_key_exists($source['source'], $orderData)) {
        echo "<b>Marketingkanaal " . $source['source'] . "</b><br />";

        $marketingchannel = R::findOne('marketingchannel',
            'name = ?',
            array($source['source']));

        if ($marketingchannel == null) {
            $marketingchannel = R::dispense('marketingchannel');
            $marketingchannel->name = $source['source'];

            echo "Bestaat nog niet en wordt toegevoegd.. <br />";
        }

        $marketingchannelrevenue = R::dispense('marketingchannelrevenue');
        $marketingchannelrevenue->channel_revenue = $source['transactionRevenue'];
        $marketingchannelrevenue->timestamp = $now;
        $marketingchannel->ownMarketingchannelrevenue[] = $marketingchannelrevenue;

        echo "Heeft " . $source['transactionRevenue'] . " omzet gedraaid.. <br /><br />";

        foreach ($orderData[$source['source']] as $orderKey => $orderValue) {
            $magentoOrderDetails = $mClient->getSalesOrderDetails($orderKey);
            //$magentoOrder = new MagentoOrder($magentoOrderDetails['order_id'], $magentoOrderDetails['base_shipping_amount']);
            $base_shipping_amount = $magentoOrderDetails['base_shipping_amount'] ;

            foreach ($magentoOrderDetails['items'] as $mProduct) {
                echo "<b>Product: " . $mProduct['name'] . "</b><br />";

                // SKU is more accurate
                $product = R::findOne(
                    'product',
                    'sku = ?',
                    array(
                        $mProduct['sku']
                    ));

                if ($product == null) {
                    // When the product doesn't excists create the object
                    $product = R::dispense('product');
                    $product->name = $mProduct['name'];
                    $product->sku = $mProduct['sku'];

                    echo "Wordt toegevoegd  (base: " . $mProduct['base_cost'] . " price: " . $mProduct['price'] . " tax: " . $mProduct['tax_amount'] . " verkocht: " . $mProduct['qty_ordered'] . ")<br />";

                    // Set its prices, because those are unknow to
                    $productprice = R::dispense('productprice');
                    $productprice->base_cost = $mProduct['base_cost'];
                    $productprice->price = $mProduct['price'];
                    $productprice->tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                    $productprice->base_shipping_amount = ($base_shipping_amount / $mProduct['qty_ordered']);
                    $productprice->timestamp = $now;

                    // The quantity is unknown, create that!
                    $productquantity = R::dispense('productquantity');
                    $productquantity->quantity = $mProduct['qty_ordered'];
                    $productquantity->timestamp = $now;

                    // Create a link to product
                    $product->ownProductprice[] = $productprice;
                    $product->ownProductquantity[] = $productquantity;
                    R::store($product); // store that bitch!
                    echo "Heeft id " . $product->id . "<br />";

                    // First time we have to add this quantity
                    $marketingchannel->ownProductquantity[] = $productquantity;

                } else {

                    echo $mProduct['name'] . " bestaat al, prijzen controleren.. <br />";

                    // When the product does excists we have to compare the prices.
                    $productprices = R::find(
                        'productprice',
                        'productprice_id = :product_id ORDER BY :sort DESC LIMIT 1',
                        array(
                            ':product_id' => $product->id(),
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
                            echo "base cost verschilt<br />";
                        } elseif ($pProductprice->price != $mProduct['price']) {
                            $price = $mProduct['price'];
                            $price = true;
                            echo "price verschilt<br />";
                        } elseif ($pProductprice->tax_amount != $mProduct['tax_amount']) {
                            $tax_amount = ($mProduct['tax_amount'] / $mProduct['qty_ordered']);
                            $price = true;
                            echo "tax verschilt<br />";
                        }

                        // When the price did change set the variables and add the new product price to the array
                        if ($price) {
                            $productprice = R::dispense('productprice');
                            $productprice->basecost = $basecost;
                            $productprice->price = $price;
                            $productprice->tax_amount = $tax_amount;
                            $productprice->base_shipping_amount = ($base_shipping_amount / $mProduct['qty_ordered']);
                            $product->ownProductprice[] = $productprice;
                            echo  $mProduct['name'] . " is voorzien van nieuwe prijzen.<br />";
                        } else {
                            echo "De prijs is niet veranderd.<br />";
                        }
                    }

                    echo "Nu het aantal verkocht ophogen..<br />";


                    //R::debug(true);
                    // If this product got sold before trough this channel add the quantity to this product
                    $productquantities = $product->ownProductquantity;
                    foreach ($productquantities as $productquantity){
                        $productquantity->quantity = ((int)$productquantity->quantity) + ((int)$mProduct['qty_ordered']);
                        print_r('nieuwe quantity: ' . $productquantity->quantity);
                        $product->ownProductquantity[] = $productquantity;
                        $marketingchannel->ownProductquantity[] = $productquantity;
                    }
                    R::store($product);
                    echo "De prijs of de quantity zijn veranderd. We update het zaakje..<br />";


                }
                echo "<br />";
            }
        }
        // Store this channel with all the objects related to it.
        R::store($marketingchannel);
    }
}
