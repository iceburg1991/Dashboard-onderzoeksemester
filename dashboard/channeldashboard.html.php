<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
<div id='dashboard'>
    <div id="marketing_channel_tables">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Verkoopprijs</th>
                <th>Inkoopprijs</th>
                <th>Verzendkosten</th>
                <th>BTW</th>
                <th>Aantal</th>
                <th>Omzet</th>
                <th>Totale kosten</th>
                <th>Bruto winst</th>
                <th>Vaste lasten</th>
                <th>Totale kosten</th>
                <th>Winst</th>
                <th>Rendement</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (sizeof($this->productsbychannelArray) > 0)
            {
                foreach ($this->productsbychannelArray as $data)
                {
                    $cost = (COSTS / $this->soldProducts) * $data->quantity;

                    $this->calculator->setCosts($cost);
                    $this->calculator->setRatio($data->revenue / $this->totalRevenue);
                    $this->calculator->setRevenue($data->revenue);
                    $this->calculator->setSpecificCosts($data->costs);

                    ?>
                    <tr class="<?= ($this->calculator->getEfficiency() > 0) ? 'success' : 'error' ?>">
                        <td><strong><?=$data->name?></strong></td>
                        <td>&euro;<?=round($data->price, 2)?></td>
                        <td>&euro;<?=round($data->base_cost, 2)?></td>
                        <td>&euro;<?=round($data->base_shipping_amount, 2)?></td>
                        <td>&euro;<?=round($data->tax_amount, 2)?></td>
                        <td><?=$data->quantity?></td>
                        <td>&euro;<?=round($data->revenue, 2)?></td>
                        <td>&euro;<?=round($data->costs, 2)?></td>
                        <td>&euro;<?=round($data->profit, 2)?></td>
                        <td>&euro;<?=round($cost, 2)?></td>
                        <td>&euro;<?=$this->calculator->getTotalCosts()?></td>
                        <td>&euro;<?=$this->calculator->getProfit()?></td>
                        <td><?=$this->calculator->getEfficiency()?>%</td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>