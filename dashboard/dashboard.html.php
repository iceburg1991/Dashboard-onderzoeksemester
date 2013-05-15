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
                            <h1 style="color: <?=($this->isPositiveProfit['kieskeurig.nl'])?'green':'red'?>"><strong>€5000,-</strong></h1>
                        </div>
                    </td>
                    <td>
                        <div>
                            <h1>Beslist.nl</h1>
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
                    <tr><th>Kieskeurig.nl</th></tr>
                </thead>
                <tbody>
                <tr>
                    <td>Omzet</td>
                    <td>Kosten</td>
                    <td>Winst</td>
                    <td>Rendement</td>
                </tr>
                <tr>
                    <td>€15.000,-</td>
                    <td>€10.000,-</td>
                    <td>€5000,-</td>
                    <td>50%</td>
                </tr>
                </tbody>
            </table>
            <table class="table table-hover">
                <thead>
                <tr><th>Beslist.nl</th></tr>
                </thead>
                <tbody>
                <tr>
                    <td>Omzet</td>
                    <td>Kosten</td>
                    <td>Winst</td>
                    <td>Rendement</td>
                </tr>
                <tr>
                    <td>€10.000,-</td>
                    <td>€7500,-</td>
                    <td>€2500,-</td>
                    <td>33%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>