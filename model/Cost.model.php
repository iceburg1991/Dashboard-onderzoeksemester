<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dylan
 * Date: 16-5-13
 * Time: 13:56
 * To change this template use File | Settings | File Templates.
 */
define("MONTH_COST", "vaste_lasten");
define("MARKETING_COST", "marketing_cost");

class Model_Cost extends RedBean_SimpleModel {

    public $cost;
    public $type;
    public $timestamp;

}