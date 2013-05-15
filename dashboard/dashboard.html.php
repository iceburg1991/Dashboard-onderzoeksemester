<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
    <div id='dashboard'>
        <h1><?= $this->title; ?></h1>
        <div id="marketing_channels">
            <table class="table">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div>
                            <h1><strong>Kieskeurig.nl</strong></h1>
                            <h1 style="color: <?=($this->totalProfitArray['kieskeurig.nl']>0)?'green':'red'?>">
                                <strong>€<?= $this->totalProfitArray['kieskeurig.nl'] ?></strong></h1>
                        </div>
                    </td>
                    <td>
                        <div>
                            <h1><strong>Beslist.nl</strong></h1>
                            <h1><strong>€2500,-</strong></h1>
                        </div>
                    </td>
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