<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijsbrand
 * Date: 20-5-13
 * Time: 14:57
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__) . '/Template.php';
require_once dirname(__FILE__) . '/channeldashboard.php';

class _GoogleChart_init
{
    public function showGoogleChart()
    {
        $googleChart = new _GoogleChart();
        $googleChart = get_object_vars($googleChart);
        var_dump($googleChart);
        $main = new Template('_googleChart.html.php', $googleChart);
        return $main->render();
    }
}

?>