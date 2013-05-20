<?

require_once dirname(__FILE__) . '/Template.php';
require_once dirname(__FILE__) . '/../classes/Calculator.class.php';

class Dashboard
{
    //public $title = 'Dashboard :-)';
    public $totalRevenue;
    public $totalProfitArray;
    public $revenueCostProfitArray;
    public $googleChart;
    public $calculator;

    private $from;
    private $to;

    public function __construct($scope = 7)
    {
        $this->from = date('Y-m-d', time() - $scope * 24 * 60 * 60);
        $this->to = date('Y-m-d H:i:s', time());

        $this->setTotalProfitArray();
        $this->setRevenueCostProfitArray();
        $this->createGoogleChart();
        $this->getTotalRevenue();
        $this->calculator = new Calculator();
    }

    private function setTotalProfitArray()
    {

        $q = "  SELECT
                mc.id AS id,
                mc.name AS name,
                SUM(mcr.channel_revenue) AS revenue
                FROM marketingchannel mc, marketingchannelrevenue mcr
                WHERE mcr.timestamp >= \"" . $this->from . "\"
                AND mcr.timestamp <= \"". $this->to . "\"
                AND mc.id = mcr.marketingchannel_id
                GROUP BY mc.name";

        // Debug
        //echo $q;

        // Data
        $rows = R::getAll($q);

        // Extract data
        $channels = R::convertToBeans('marketingchannelrevenue', $rows);

        // Add to this array
        foreach ($channels as $channel){
            $this->totalProfitArray[$channel->name] = $channel->revenue;
        }

        // Return shat
        return;
    }

    private function setRevenueCostProfitArray()
    {
         $q = " SELECT
                mc.id,
                mc.name,
                SUM(DISTINCT(mcr.channel_revenue)) AS channelrevenue,
                SUM((pr.base_cost + pr.tax_amount) * pq.quantity) AS cost, SUM((pr.price - pr.base_cost - pr.tax_amount) * pq.quantity) AS profit
                FROM productprice pr, productquantity pq, marketingchannel mc, marketingchannelrevenue mcr
                WHERE pr.product_id = pq.product_id
                AND mc.id  = pq.marketingchannel_id
                AND mcr.marketingchannel_id = pq.marketingchannel_id
                AND pq.timestamp >= \"" . $this->from . "\"
                AND pq.timestamp <= \"" . $this->to . "\"
                GROUP BY mc.id";

        // Debug
        //echo $q;

        // Data
        $rows = R::getAll($q);

        // Extract data
        $results = R::convertToBeans('marketingchannelrevenue', $rows);

        foreach ($results as $result) {
            $this->revenueCostProfitArray[] = $result;
        }

        return;
    }

    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }

    private function getTotalRevenue()
    {
        $q =
            "   SELECT
                id,
                SUM( channel_revenue ) AS revenue
                FROM marketingchannelrevenue
                WHERE timestamp >= \"" . $this->from . "\"
                AND timestamp <= \"" . $this->to .  "\"";

        // Debug
        //echo $q;

        $rows = R::getAll($q);

        // Extract data
        $results = R::convertToBeans('marketingchannelrevenue', $rows);

        foreach ($results as $result) {
            $this->totalRevenue = $result->revenue;
        }

        return;
    }
}
?>