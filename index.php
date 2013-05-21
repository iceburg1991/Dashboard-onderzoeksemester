<?php
// Session start
session_start();

// Turn on all error reporting
error_reporting(E_ALL);

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

    echo dirname(__FILE__);

    $scope = 1;
    $from = 'day';

    if (isset($_GET['from'])) {
        $from = $_GET['from'];
        if ($from == 'week') {
            $scope = 7;
        } elseif ($from == 'month') {
            $scope = 30;
        }
    }

    require_once dirname(__FILE__) . '/dashboard/dashboard_init.php';
    $dashboard_init = new Dashboard_init();
    $dashboard_init->showDashboard($scope);
    ?>
</div>
</section>
</body>
</html>