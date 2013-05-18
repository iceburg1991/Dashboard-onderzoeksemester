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
                                <h1><strong><?=$channel?></strong></h1>

                                <h1 style="color: <?= ($profit > 0) ? 'green' : 'red' ?>">
                                    <strong>€<?= $profit ?></strong></h1>
                            </div>
                        </td>
                        <?php
                    }
                } else {
                    ?>
                    <td>
                        <div>
                            <h1><strong>Kapodt</strong></h1>
                            <h1><strong>Ga eens kanalen toevoegen of producten verkopen</strong></h1>
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
                foreach ($this->revenueCostProfitArray as $data)
                {
                ?>
                <tr class="<?= ($data->profit > 0) ? 'success' : 'error' ?>">
                    <td><strong><a href="channel.php?id=<?= $data->id ?>"><?=$data->name?></a></strong></td>
                    <td>&euro;<?=round($data->channelrevenue, 2)?></td>
                    <td>&euro;<?=round($data->productrevenue, 2)?></td>
                    <td>&euro;<?=round($data->cost, 2)?></td>
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