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

if (isset($_COOKIE['scope'])) {
    $from = $_COOKIE['scope'];
    switch($from) {
        case 'day':
            $scope = 1;
            break;
        case 'week':
            $scope = 7;
            break;
        case 'month':
            $scope = 31;
            break;
    }
} else {
    $value = 'day';
    setcookie('scope', $value);
    $scope = 1;
}

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

        $dashboard_init = new ChannelDashboard_init();
        $dashboard_init->showDashboard($_GET['id'], $scope);

        ?>
    </div>
</section>
</body>
</html>