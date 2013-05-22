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
    $scope = $_COOKIE['scope'];
} else {
    $scope = 1;
    setcookie('scope', $scope);
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
        if (isset($_REQUEST['id'])) {
            $dashboard_init = new ChannelDashboard_init();
            $dashboard_init->showDashboard($_REQUEST['id'], $scope);
        } else {
            $marketingChannels = R::findAll('marketingchannel');
            echo "<p>please choose a marketingchannel</p>";
            foreach ($marketingChannels as $marketingChannel) {
                echo "<a href=\"channel.php?id=" . $marketingChannel->id . "\">" . $marketingChannel->name . "</a> <br />";
            }
        }
        ?>
    </div>
</section>
</body>
</html>