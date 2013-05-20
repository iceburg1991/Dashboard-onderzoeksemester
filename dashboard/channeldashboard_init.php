<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 18-05-13
 * Time: 16:46
 * To change this template use File | Settings | File Templates.
 */
include dirname(__FILE__) . '/Template.php';
require_once dirname(__FILE__) . '/channeldashboard.php';

class ChannelDashboard_init
{
    public function showDashboard($channelId, $scope = 7)
    {
        $dashboard = new ChannelDashboard($channelId, $scope);
        $dashboard = get_object_vars($dashboard);
        $main = new Template('channeldashboard.html.php', $dashboard);
        return $main->render();
    }

    /*public function showDashboard(){
        $dashboard = new Dashboard();

        $main = new Template('dashboard.html.php', array(
            'title' => $dashboard->title,
            'isPositiveProfit' => $dashboard->aIsPositiveProfit
        ));
        return $main->render();
    }*/
}

?>