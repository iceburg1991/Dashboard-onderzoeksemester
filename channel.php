<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 18-05-13
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */

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
?>
<section class="onerow full color1">
    <div class="onepcssgrid-1200">
        <?php
        require_once dirname(__FILE__) . '/dashboard/channeldashboard_init.php';

        $scope = 1;

        if (isset($_GET['from'])) {
            $from = $_GET['from'];
            if ($from == 'week') {
                $scope = 7;
            } elseif ($from == 'month') {
                $scope = 30;
            }
        }

        $dashboard_init = new ChannelDashboard_init();
        $dashboard_init->showDashboard($_GET['id'], $scope);

        ?>
    </div>
</section>
</body>
</html>