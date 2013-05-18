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
        $to = date('Y-m-d H:00:00', time() + 2 * 60 * 60); // +2 hours because of the server time.

        // Data
        $rows = R::getAll("SELECT mc.id AS id, mc.name AS name, SUM(mcr.channel_revenue) AS revenue FROM marketingchannel mc, marketingchannelrevenue mcr WHERE mcr.timestamp >= '" . $from . "' AND mcr.timestamp <= '". $to . "' AND mc.id = mcr.marketingchannel_id GROUP BY mc.name");
        // Extract data
        $channels = R::convertToBeans('marketingchannelrevenue', $rows);

        // Add to this array
        foreach ($channels as $channel){
            $this->totalProfitArray[$channel->name] = $channel->revenue;
        }

        // Return shat
        return;
    }

    /*
    SELECT mc.name, SUM(mcr.channel_revenue) AS revenue, SUM((pr.price - pr.base_cost - pr.tax_amount) * pq.quantity) AS profit
    FROM product p, productprice pr, productquantity pq, marketingchannel mc, marketingchannelrevenue mcr
    WHERE pr.product_id = p.id
    AND pq.product_id = p.id
    AND pq.marketingchannel_id = mc.id
    AND mcr.marketingchannel_id = mc.id
    GROUP BY mc.name

    SELECT p.name, pq.quantity, mc.name, pr.price, pr.base_cost, pr.tax_amount, ((pr.price - pr.base_cost - pr.tax_amount) * pq.quantity) AS profitmade
    FROM product p, productquantity pq, marketingchannel mc, productprice pr
    WHERE pq.product_id = p.id
    AND mc.id = pq.marketingchannel_id
    AND pr.product_id = p.id
    AND mc.name = 'hugozonderland.nl'
    */

    //private function get

    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }
}

?>