<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
<div id='dashboard'>
    <div id="marketing_channels">
        <table class="table">
            <thead>
            </thead>
            <tbody>
            <tr>
                <?php
                if (sizeof($this->totalProfitArray) > 0) {
                    foreach ($this->totalProfitArray as $channel => $profit)
                    {
                        ?>
                        <td>
                            <div>
                                <h2><strong><?=$channel?></strong></h2>

                                <h2 style="color: <?= ($profit > 0) ? 'green' : 'red' ?>">
                                    <strong>€<?= $profit ?></strong></h2>
                            </div>
                        </td>
                        <?php
                    }
                } else {
                    ?>
                    <td>
                        <div>
                            <h1><strong>Kapodt</strong></h1>
                            <h1><strong>Ga eens producten verkopen</strong></h1>
                        </div>
                    </td>
                    <?php
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="marketing_channel_tables">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Marketing kanaal</th>
                <th>Kanaal omzet</th>
                <th>Product omzet</th>
                <th>Kosten</th>
                <th>Winst</th>
                <th>Rendement</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (sizeof($this->revenueCostProfitArray) > 0)
            {
                $this->calculator->setCosts(COSTS);

                foreach ($this->revenueCostProfitArray as $data)
                {
                    $this->calculator->setRatio($this->totalRevenue / $data->channelrevenue);
                    $this->calculator->setRevenue($data->channelrevenue);
                    $this->calculator->setSpecificCosts($data->cost);
                ?>
                <tr class="<?= ($data->profit > 0) ? 'success' : 'error' ?>">
                    <td><strong><a href="channel.php?id=<?= $data->id ?>"><?=$data->name?></a></strong></td>
                    <td>&euro;<?=round($data->channelrevenue, 2)?></td>
                    <td>&euro;<?=round($data->productrevenue, 2)?></td>
                    <td>&euro;<?=round($data->cost, 2)?></td>
                    <td>&euro;<?=round($data->profit, 2)?></td>
                    <td><?=$this->calculator->getEfficiency()?>%</td>
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