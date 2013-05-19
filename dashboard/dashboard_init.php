<?php
include dirname(__FILE__) . '/Template.php';
require_once dirname(__FILE__) . '/dashboard.php';

class Dashboard_init
{

    public function showDashboard($scope = 7)
    {
        $dashboard = new Dashboard($scope);
        $dashboard = get_object_vars($dashboard);
        $main = new Template('dashboard.html.php', $dashboard);
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