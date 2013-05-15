<?

class Dashboard{

    public $title = 'Dashboard :-)';
    public $aIsPositivePrice;

    public function __construct(){
        $this->isPositiveProfit();
    }
    private function isPositiveProfit(){
        $result = false;
        $price = 5000;

        if($price > 0){
            $result = true;
        }
        $this->aIsPositiveProfit = array(
            'kieskeurig.nl' => $result
        );
        return;
    }
}
?>