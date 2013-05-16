<?
require_once dirname(__FILE__) . '/Template.php';
class Dashboard
{

    //public $title = 'Dashboard :-)';
    public $totalProfitArray;
    public $googleChart;

    public function __construct()
    {
        $this->setTotalProfitArray();
        $this->createGoogleChart();
    }

    private function setTotalProfitArray()
    {
        // 7 day time filter
        $from = date('Y-m-d', time() - 7 * 24 * 60 * 60);
        $to = date('Y-m-d H:00:00', time() + 2 * 60 * 60);

        $rows = R::getAll("SELECT mc.id AS id, mc.name AS name, SUM(mcr.channel_revenue) AS revenue FROM marketingchannel mc, marketingchannelrevenue mcr WHERE mcr.timestamp >= '" . $from . "' AND mcr.timestamp <= '". $to . "' AND mc.id = mcr.marketingchannel_id GROUP BY mc.name");
        $channels = R::convertToBeans('marketingchannelrevenue', $rows);

        foreach ($channels as $channel){
            $this->totalProfitArray[$channel->name] = $channel->revenue;
        }

        return;
    }

    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }
}

?>