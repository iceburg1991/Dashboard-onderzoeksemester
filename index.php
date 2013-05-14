<?php
// Session start
session_start();

// Turn on all error reporting
error_reporting(-1);

// Include all required files
require_once dirname(__FILE__) . '/includes.php';
?>
<!DOCTYPE html>
<html lang="nl-NL" prefix="og:http://ogp.me/ns#" class="csstransforms csstransforms3d csstransitions js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="http://www.presteren.nu/wp-content/themes/creolio-child/favicon.ico">
    <link href="http://fonts.googleapis.com/css?family=Quattrocento:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://www.presteren.nu/wp-content/themes/creolio-child/style.css">
    <!--[if IE 8]>
    <link rel="stylesheet" href="http://www.presteren.nu/wp-content/themes/creolio/assets/core/libs/css/ie8.css">
    <![endif]-->
    <link rel="stylesheet" id="rwcss-css"
          href="http://www.presteren.nu/wp-content/themes/creolio-child/rw-core.css?ver=1.1"
          type="text/css" media="all">
    <link rel="stylesheet" id="rwcsscolor-css"
          href="http://www.presteren.nu/wp-content/themes/creolio-child/rw-colors.css?ver=1.1" type="text/css"
          media="all">
    <script type="text/javascript" src="http://www.presteren.nu/wp-includes/js/jquery/jquery.js?ver=1.8.3"></script>
    <link rel="stylesheet" href="css/style_override.css">
    <title>esser-emmerik | Rendements berekening</title>
</head>
<body class="page page-id-49 page-template-default theme1 ie">

<header class="onerow color2">
    <div class="onepcssgrid-1200">
        <div class="col4 iconic icon-ok">
            <div class="title">
                <a href="http://www.presteren.nu">esser-emmerik</a>
            </div>
            <div>online verkoop experts</div>
            <div class="phone icon-phone" onclick="location.href='tel:0357009703';">
                <span><a href="tel:0357009703">035 - 7009 703</a></span>
            </div>
        </div>
        <nav class="col8 last">
            <div>
                <ul id="mainnav">
                    <?php
                    if (isset($_GET['costs']) && strlen($_GET['costs']) > 0) {
                        echo "<li id=\"menu-item-48\"><a href=\"index.php?propertyId=" . $_GET['propertyId'] . "&accountId=" . $_GET['accountId'] . "&profileId=" . $_GET['profileId'] . "\">Verander vaste lasten</a></li>";
                    }
                    ?>
                    <li id="menu-item-48"><a href="index.php">Selecteer een ander account</a></li>
                    <li id="menu-item-288"><a href="index.php?logout">Uitloggen</a></li>
                </ul>
                <select class="selectnav" id="selectnav1" style="width: 1357px; ">
                    <option value="">Main navigation</option>
                    <option value="index.php" selected="">Selecteer een ander account</option>
                    <option value="index.php?logout">Uitloggen</option>
                </select>
                <i class="selnav icon-align-justify"></i>
            </div>
        </nav>
        <div class="arrow"></div>
    </div>
</header>

<?php
/**
 * @TODO: make this part of code more senseable
 */
// The only file that has to be included in the body for style purposes.
require_once dirname(__FILE__) . '/clients/GoogleAnalyticsAccountSelector.php';
?>
<section class="onerow full color1">
<div class="onepcssgrid-1200">
    <?php

    if ((isset($GoogleAnalyticsAccount)) && (sizeof($GoogleAnalyticsAccount->getProperties() > 0)) && $GoogleAnalyticsAccount != null) {

        require_once dirname(__FILE__) . '/dashboard/dashboard_init.php';
        $dashboard_init = new Dashboard_init();
        $dashboard_init->showDashboard();

        //if (isset($_GET['costs']) && strlen($_GET['costs']) > 0) {

            echo 'wajow werkt dit?';

            // 30 day time filter
            $from = date('Y-m-d', time() - 30 * 24 * 60 * 60);
            $to = date('Y-m-d');

            // Fetches all the Revenue metrics
            $TransactionRevenueMetrics = new TransactionRevenueMetrics($service, $_GET['profileId'], $from, $to);

            // Fetches the orders per channel
            $OrderPerMarketingChannel = new OrderPerMarketingChannel($service, $_GET['profileId'], $from, $to);
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
                        //echo "<div class=\"col6\" style=\"border-right: 1px #000 solid\">";
                    }

                    if ($source['source'] == "kieskeurig.nl") {
                        $clickCosts = 250; // specific cost
                        //echo "<div class=\"col6 last\">";
                    }

                }
            }




                    /*
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
        }
        else {
            ?>
            <form name="costs" method="get">
                <h3>Vaste lasten per maand:</h3>

                <p><input type="text" name="costs" required/></p>

                <p><input type="submit" value="Verstuur!"/></p>

                <input type="hidden" name="propertyId" value="<?php echo $_GET['propertyId']; ?>"/>
                <input type="hidden" name="accountId" value="<?php echo $_GET['accountId']; ?>"/>
                <input type="hidden" name="profileId" value="<?php echo $_GET['profileId']; ?>"/>
            </form>
        <?php
        }*/
    }
    ?>
</div>
</section>
</body>
</html>