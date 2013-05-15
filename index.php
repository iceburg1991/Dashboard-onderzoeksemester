<?php
// Session start
session_start();

// Turn on all error reporting
error_reporting(-1);

// Include all required files
require_once dirname(__FILE__) . '/includes.php';

// Header HTML
require_once dirname(__FILE__) . '/includes/header.php';

// Navigation HTML
require_once dirname(__FILE__) . '/includes/nav.php';
/**
 * @TODO: make this part of code more senseable
 */
?>
<section class="onerow full color1">
<div class="onepcssgrid-1200">
    <?php

    //if ((isset($GoogleAnalyticsAccount)) && (sizeof($GoogleAnalyticsAccount->getProperties() > 0)) && $GoogleAnalyticsAccount != null) {

       //require_once dirname(__FILE__) . '/dashboard/dashboard_init.php';
        //$dashboard_init = new Dashboard_init();
        //$dashboard_init->showDashboard();

        //if (isset($_GET['costs']) && strlen($_GET['costs']) > 0) {

            // 30 day time filter
            $from = date('Y-m-d', time() - 30 * 24 * 60 * 60);
            $to = date('Y-m-d');

            // Fetches all the Revenue metrics
            $TransactionRevenueMetrics = new TransactionRevenueMetrics($service, $settings->google_analytics_profile_id, $from, $to);

            // Fetches the orders per channel
            $OrderPerMarketingChannel = new OrderPerMarketingChannel($service, $settings->google_analytics_profile_id, $from, $to);
            $orderData = $OrderPerMarketingChannel->getOrdersPerChannel(); // all the order data

            // Costs this orgnisation has, per month now
            //$costs = $_GET['costs'];
             $costs = '5000';
            // Calulator
            $calc = new Calculator();
            $calc->setCosts($costs);

            foreach ($TransactionRevenueMetrics->getRevenuePerSource() as $source) {

                // set vars
                $calc->setRevenue($TransactionRevenueMetrics->getTotalRevenue());
                $calc->setRatio($source['transactionRevenue'] / $TransactionRevenueMetrics->getTotalRevenue());

                // set and reset basecosts
                $basecosts = 0;


                if ($source['source'] == "beslist.nl" || $source['source'] == "kieskeurig.nl" || $source['source'] == "beslistslimmershoppen") {

                    if ($source['source'] == "beslist.nl") {
                        $clickCosts = 125; // specific costs
                        echo "<div class=\"col6\" style=\"border-right: 1px #000 solid\">";
                    }

                    if ($source['source'] == "kieskeurig.nl") {
                        $clickCosts = 250; // specific cost
                        echo "<div class=\"col6 last\">";
                    }

                    $orders = array();
                    $productsOrderd = 0;

                    if ($source['source'] != "(direct)") {
                        // calculate profit over orders in Magento
                        foreach ($orderData[$source['source']] as $orderKey => $orderValue) {
                            $magentoOrderDetails = $mClient->getSalesOrderDetails($orderKey);
                            //echo "<pre>";
                            //print_r($magentoOrderDetails);
                            //echo "</pre>";
                            //die();
                            $magentoOrder = new MagentoOrder($magentoOrderDetails['order_id'], $magentoOrderDetails['base_shipping_amount']);
                            foreach ($magentoOrderDetails['items'] as $product) {
                                $magentoProduct = new MagentoProduct($product['item_id'], $product['sku'], $product['name'], $product['base_cost'], $product['price'], ($product['tax_amount'] / $product['qty_ordered']), $product['qty_ordered']);
                                $magentoOrder->addProduct($magentoProduct);
                                $productsOrderd += $product['qty_ordered'];
                                $basecosts += $magentoProduct->getBaseCost();
                            }
                            array_push($orders, $magentoOrder);
                        }

                        //echo "<pre>";
                        //print_r($orders);
                        //echo "</pre>";
                        //die();

                        // Set specific costs
                        $specificCosts = $clickCosts;
                        $calc->setSpecificCosts($specificCosts);

                        echo "<h1 class=\"ic\">" . $source['source'] . "</h1><br />";
                        echo "<h3>Omzet uit Google Analytics</h3>";
                        echo "<p>";
                        echo "Totale omzet = &euro;" . $calc->getRevenue() . "<br />";
                        echo "Omzet " . $source['source'] . " = &euro;" . $source['transactionRevenue'] . "<br />";
                        echo "Verhoudingspercentage = " . $calc->getRatioReadable() . "% (&euro;" . $source['transactionRevenue'] . " / &euro;" . $TransactionRevenueMetrics->getTotalRevenue() . ")<br /><br />";
                        echo "</p>";

                        echo "<h3>Google Analytics en vaste kosten en kosten marketingkanaal</h3>";
                        echo "<p>";
                        echo "Totale Kosten per maand = &euro;" . $calc->getCosts() . "<br />";
                        echo "Kosten " . $source['source'] . " naar verhouding  = &euro;" . $calc->getCostRatioReadable() . " (&euro;" . $costs . " / " . $calc->getRatioReadable() . "%)<br />";
                        echo "Klikkosten " . $source['source'] . " = &euro;" . $clickCosts . "<br />";
                        echo "Winst: &euro;" . $calc->getProfitRatioSpecificReadable() . " (&euro;" . $source['transactionRevenue'] . " - &euro;" . $calc->getCostRatioReadable() . " - &euro;" . $clickCosts . ") <br />";
                        echo "Rendement nauwkeurigheids niveau 1: " . $calc->getEfficiency() . "%<br /><br />";
                        echo "</p>";

                        // Set specific costs
                        $specificCosts = $source['transactionTax'] + $source['transactionShipping'] + $clickCosts + $basecosts;
                        $calc->setSpecificCosts($specificCosts);

                        // Efficiency
                        $efficiency = $calc->getEfficiency();

                        // Color effect
                        if ($efficiency > 10) {
                            $color = "#00FF00";
                        } elseif ($efficiency > 0 && $efficiency < 10) {
                            $color = "#FF6929";
                        } else {
                            $color = "#FF0000";
                        }

                        echo "<h3>Google Analytics en vastekosten en specifieke kosten en inkoopkosten Magento</h3>";
                        echo "<p>";
                        echo "Totale Kosten per maand = &euro;" . $calc->getCosts() . "<br />";
                        echo "Kosten " . $source['source'] . " naar verhouding  = &euro;" . $calc->getCostRatioReadable() . " (&euro;" . $costs . " / " . $calc->getRatioReadable() . "%)<br />";
                        echo "Klikkosten " . $source['source'] . "= &euro;" . $clickCosts . "<br />";
                        echo "Belasting = &euro;" . $source['transactionTax'] . "<br />";
                        echo "Verzendkosten = &euro;" . $source['transactionShipping'] . "<br />";
                        echo "Inkoopkosten = &euro;" . $basecosts . "<br />";
                        echo "Specifieke kosten = klikkosten + belasting + inkoopkosten + verzend: &euro;" . $calc->getSpecificCosts() . " (&euro;" . $calc->getCostRatioReadable() . " + &euro;" . $clickCosts . " + &euro;" . $source['transactionTax'] . " + &euro;" . $source['transactionShipping'] . " + &euro;" . $basecosts . ")<br />";
                        echo "Winst: &euro;" . $calc->getProfitRatioSpecificReadable() . " (&euro;" . $source['transactionRevenue'] . " - &euro;" . $calc->getSpecificCosts() . " - &euro;" . $calc->getCostRatioReadable() . ")<br />";
                        echo "Rendement nauwkeurigheidsniveau 4: <span style=\"color: $color;\"><strong>" . $calc->getEfficiency() . "%</strong></span>";
                        echo "</p>";

                        $arrays = array();
                        foreach ($orders as $order) {
                            foreach ($order->getProducts() as $product) {
                                if (!array_key_exists($product->getProductSku(), $arrays)) {
                                    $productArray = array();
                                    $productArray['magento_id'] = $product->getMagentoId();
                                    $productArray['sku'] = $product->getProductSku();
                                    $productArray['name'] = $product->getName();
                                    $productArray['price'] = $product->getPrice();
                                    $productArray['base_cost'] = $product->getBaseCost();
                                    $productArray['profit'] = $product->getProductProfit();
                                    $productArray['tax_amount'] = $product->getTaxAmount();
                                    $productArray['qty_ordered'] = $product->getQuantity();
                                    $productArray['shipping_costs'] = ($order->getShippingCost() / sizeof($order->getProducts()));
                                    $arrays[$product->getProductSku()] = $productArray;
                                } else {
                                    $arrays[$product->getProductSku()]['qty_ordered'] += $product->getQuantity();
                                    $arrays[$product->getProductSku()]['shipping_costs'] += ($order->getShippingCost() / sizeof($order->getProducts()));
                                }
                            }
                        }

                        echo "<h1 class=\"ic\">Producten</h1><br />";

                        ksort($arrays);

                        foreach ($arrays as $product) {
                            $productRevenue = $product['price'] * $product['qty_ordered'];
                            $ratio = $productRevenue / $source['transactionRevenue'];
                            $productCostRatio = $calc->calculateCostRatio() * $ratio;
                            $clickCostsProduct = $clickCosts / $productsOrderd;

                            echo "<h3>Rendement " . $product['name'] . " </h3>";
                            echo "<p>";
                            echo "Omzet: &euro;" . $productRevenue . "<br />";
                            echo "Vaste lasten: &euro;" . round($productCostRatio, 2) . "<br />";
                            echo "Winst: &euro;" . round($productRevenue - $productCostRatio, 2) . "<br />";
                            echo "Rendement: " . round(($productRevenue - $productCostRatio) / $productCostRatio * 100, 2) . "%<br />";
                            echo "<br />";

                            echo "Omzet: &euro;" . $productRevenue . "<br />";
                            echo "Vaste lasten: &euro;" . round($productCostRatio, 2) . "<br />";

                            $productCostRatio += $product['shipping_costs'] + $product['tax_amount'] + ($clickCostsProduct * $product['qty_ordered']) + ($product['base_cost'] * $product['qty_ordered']);

                            echo "Belasting: &euro;" . round($product['tax_amount'], 2) . "<br />";
                            echo "Inkoopkosten: &euro;" . round($product['base_cost'] * $product['qty_ordered'], 2) . "<br />";
                            echo "Verzendkosten: &euro;" . round($product['shipping_costs'], 2) . "<br />";
                            echo "Klikkosten product: &euro;" . round(($clickCostsProduct * $product['qty_ordered']), 2) . "<br />";
                            echo "Totale Kosten: &euro;" . round($productCostRatio, 2) . "<br />";
                            echo "Winst: &euro;" . round($productRevenue - $productCostRatio, 2) . "<br />";
                            echo "Rendement: " . round(($productRevenue - $productCostRatio) / $productCostRatio * 100, 2) . "%<br />";
                            echo "</p>";

                        }
                        echo "</div>";
                    }
                }
            }
        //} else {
            ?>
            <form name="costs" method="get">
                <h3>Vaste lasten per maand:</h3>
                <p>&euro;<input type="text" name="costs" required/></p>
                <p><input type="submit" value="Verstuur!"/></p>
                <input type="hidden" name="propertyId" value="<?php echo $_GET['propertyId']; ?>"/>
                <input type="hidden" name="accountId" value="<?php echo $_GET['accountId']; ?>"/>
                <input type="hidden" name="profileId" value="<?php echo $_GET['profileId']; ?>"/>
            </form>
        <?php
        //}
    //}
    ?>
</div>
</section>
</body>
</html>