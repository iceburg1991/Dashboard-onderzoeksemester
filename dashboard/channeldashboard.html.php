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
                <th>Product naam</th>
                <th>Product prijs</th>
                <th>Product inkoopkosten</th>
                <th>Product belasting</th>
                <th>Hoeveelheid verkocht</th>
                <th>Product omzet</th>
                <th>Totale productkosten</th>
                <th>Product winst</th>
                <th>Rendement</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (sizeof($this->productsbychannelArray) > 0)
            {
                foreach ($this->productsbychannelArray as $data)
                {
                    ?>
                    <tr class="<?= ($data->profit > 0) ? 'success' : 'error' ?>">
                        <td><strong><?=$data->name?></strong></td>
                        <td>&euro;<?=round($data->price, 2)?></td>
                        <td>&euro;<?=round($data->base_cost, 2)?></td>
                        <td>&euro;<?=round($data->tax_amount, 2)?></td>
                        <td><?=$data->quantity?></td>
                        <td>&euro;<?=round($data->revenue, 2)?></td>
                        <td>&euro;<?=round($data->costs, 2)?></td>
                        <td>&euro;<?=round($data->profit, 2)?></td>
                        <td>50%</td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php $this->googleChart->render(); ?>
</div>
</body>
</html>