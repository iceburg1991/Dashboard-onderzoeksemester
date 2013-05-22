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
                <th>Omzet</th>
                <th>Kosten</th>
                <th>Winst</th>
                <th>Rendement</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (sizeof($this->revenueCostProfitArray) > 0)
            {
                foreach ($this->revenueCostProfitArray as $data)
                {
                    $ratio = round($data->channelrevenue / $this->totalRevenue, 2);
                    $cost = round(COSTS * $ratio, 2);
                    $totalcost = $cost + $data->cost;
                    $profit = $data->channelrevenue - $cost - $data->cost;
                    $efficiency = round($profit / $totalcost * 100, 2);
                ?>
                <tr class="<?= ($efficiency > 0) ? 'success' : 'error' ?>">
                    <td><strong><a href="channel.php?id=<?= $data->id?>&from=<?=$_COOKIE['scope']?>"><?=$data->name?></a></strong></td>
                    <td>&euro;<?=round($data->channelrevenue, 2)?></td>
                    <td>&euro;<?=round($cost, 2)?></td>
                    <td>&euro;<?=round($totalcost, 2)?></td>
                    <td>&euro;<?=round($profit, 2)?></td>
                    <td><?=$efficiency?>%</td>
                </tr>
                <?php
                }
            }

            ?>
            </tbody>
        </table>
    </div>
    <?php $this->googleChart->showGoogleChart();  ?>
</div>
</body>
</html>