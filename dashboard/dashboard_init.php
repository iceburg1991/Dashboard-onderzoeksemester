<?php
include dirname(__FILE__) .'/Template.php';
require_once dirname(__FILE__) . '/dashboard.php';

class Dashboard_init {

    public function showDashboard(){
        $dashboard = new Dashboard();

        $main = new Template('dashboard.html.php', array('title' => $dashboard->title));
        return $main->render();
    }
}
?>