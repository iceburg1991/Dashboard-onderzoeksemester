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
                if (sizeof($this->totalProfitArray)) {
                    foreach ($this->totalProfitArray as $channel => $profit)
                    {
                        ?>
                        <td>
                            <div>
                                <h1><strong><?=$channel?></strong></h1>

                                <h1 style="color: <?= ($profit) ? 'green' : 'red' ?>">
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
                <th>Omzet</th>
                <th>Kosten</th>
                <th>Winst</th>
                <th>Rendement</th>
            </tr>
            </thead>
            <tbody>
            <tr class="success">
                <td><strong>Kieskeurig.nl</strong></td>
                <td>€15.000,-</td>
                <td>€10.000,-</td>
                <td>€5000,-</td>
                <td>50%</td>
            </tr>
            <tr class="success">
                <td><strong>beslist.nl</strong></td>
                <td>€10.000,-</td>
                <td>€7500,-</td>
                <td>€2500,-</td>
                <td>33%</td>
            </tr>
            <tr class="error">
                <td><strong>Vergelijk</strong></td>
                <td>€2300,-</td>
                <td>€3300,-</td>
                <td>-€1000,-</td>
                <td>-9,43%</td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php $this->googleChart->render(); ?>
</div>
</body>
</html>