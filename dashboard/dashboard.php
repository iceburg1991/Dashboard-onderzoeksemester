<?
require_once dirname(__FILE__) . '/Template.php';
class Dashboard
{

    public $title = 'Dashboard :-)';
    public $totalProfitArray;
    public $googleChart;

    public function __construct()
    {
        $this->setTotalProfitArray();
        $this->createGoogleChart();
    }

    private function setTotalProfitArray()
    {
        $price = 5000;

        $this->totalProfitArray = array(
            'kieskeurig.nl' => $price
        );
        return;
    }

    private function createGoogleChart()
    {
        $this->googleChart = get_object_vars($this->googleChart);
        $this->googleChart = new Template('_googleChart.html.php', $this->googleChart);
    }
}

?>