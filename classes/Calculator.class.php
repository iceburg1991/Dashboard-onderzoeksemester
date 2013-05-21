<?php
/**
 * Class Calculator
 */
class Calculator
{

    /**
     * containing general costs (rent, ensurance etc)
     * @var float
     */
    private $_costs;

    /**
     * containing spefic costs (clicks)
     * @var float
     */
    private $_specificCosts;

    /**
     * containing revenue
     * @var float
     */
    private $_revenue;

    /**
     * containing channel ratio
     * @var float
     */
    private $_ratio;

    /**
     * Gets the costs
     * @return mixed
     */
    public function getCosts()
    {
        return $this->_costs;
    }

    /**
     * Sets the costs
     *
     * @param $costs
     */
    public function setCosts($costs)
    {
        $this->_costs = $costs;
    }

    /**
     * Gets the specific
     * @return mixed
     */
    public function getSpecificCosts()
    {
        return $this->_specificCosts;
    }

    /**
     * Sets the costs
     *
     * @param $specificCosts
     */
    public function setSpecificCosts($specificCosts)
    {
        $this->_specificCosts = $specificCosts;
    }

    /**
     * Gets the revenue
     * @return mixed
     */
    public function getRevenue()
    {
        return $this->_revenue;
    }

    /**
     * Sets the revenue
     *
     * @param $revenue
     */
    public function setRevenue($revenue)
    {
        $this->_revenue = $revenue;
    }

    /**
     * Gets the ratio
     * @return mixed
     */
    public function getRatio()
    {
        return $this->_ratio;
    }

    /**
     * Sets the ratio
     *
     * @param $ratio
     */
    public function setRatio($ratio)
    {
        $this->_ratio = $ratio;
    }


    /**
     * Returns ratio in a human readable format
     * @return float
     */
    public function getRatioReadable()
    {
        return round($this->_ratio * 100, 2);
    }

    /**
     * @return float
     */
    public function calculateCostRatio()
    {
        return $this->_costs * $this->_ratio;
    }

    /**
     * @return float
     */
    public function getCostRatioReadable()
    {
        return round($this->calculateCostRatio(), 2);
    }

    /**
     * @return float
     */
    public function calculateProfitRatioSpecific()
    {
        return (($this->_revenue - $this->_costs) * $this->_ratio) - $this->_specificCosts;
    }

    /**
     * @return float
     */
    public function getProfitRatioSpecificReadable()
    {
        return round($this->calculateProfitRatioSpecific(), 2);
    }

    /**
     * @return float
     */
    public function calculateRatioEfficiency()
    {
        //echo "(( " . $this->_revenue . " - " . $this->_costs . ") * " . $this->_ratio. ") - " . $this->_specificCosts . "/( " .$this->calculateCostRatio() ." + " .$this->_specificCosts . ")";
        return $this->calculateProfitRatioSpecific() / ($this->calculateCostRatio() + $this->_specificCosts);
    }

    /**
     * @return float
     */
    public function getRatioEfficiency()
    {
        return round($this->calculateRatioEfficiency() * 100, 2);
    }

    /**
     * Calculates total costs
     * @return float
     */
    public function calculateTotalCosts()
    {
        return $this->_costs + $this->_specificCosts;
    }

    /**
     * @return float
     */
    public function getTotalCosts()
    {
        return round($this->calculateTotalCosts(), 2);
    }

    /**
     * @return float
     */
    public function calculateProfit()
    {
        return $this->_revenue - $this->_costs - $this->_specificCosts;
    }

    /**
     * @return float
     */
    public function getProfit()
    {
        return round($this->calculateProfit(), 2);
    }

    /**
     * @return float
     */
    public function calculateEfficiency()
    {
        return $this->calculateProfit() / $this->calculateTotalCosts();
    }

    /**
     * @return float
     */
    public function getEfficiency()
    {
        return round($this->calculateEfficiency() * 100, 2);
    }
}

?>