<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 21-05-13
 * Time: 16:17
 * To change this template use File | Settings | File Templates.
 */
// Session start
session_start();

// Turn on all error reporting
error_reporting(-1);

// Include all required files
require_once dirname(__FILE__) . '/includes.php';

// Header HTML
require_once dirname(__FILE__) . '/includes/header.php';

// Navigation HTML
require_once dirname(__FILE__) . '/includes/nav.php';

?>
<section class="onerow full color1">
    <div class="onepcssgrid-1200">
        <form name="clickcost" method="post" action="clickcost.php">
            <?php
            // Data
            $channels = R::findAll('marketingchannel');

            // Add to this array
            foreach ($channels as $channel)
            {
                $clickcost = R::find(
                    'marketingchannelclickcost',
                    'marketingchannel_id = :channelid
                     ORDER BY timestamp DESC LIMIT 0,1',
                    array(
                        ':channelid' => $channel->id
                    ));

                $cpc = ($channel->cpc) ? $channel->cpc : 0;
                $clicks = ($channel->clicks) ? $channel->clicks : 0;
                ?>
                <input type="hidden" name="marketingchannel_id" value="<?=$channel->id?>" />
                <table width="80%">
                <tr>
                    <td colspan="2"><h2><?=$channel->name?> klikkosten</h2></td>
                </tr>
                <tr>
                    <td>Cost per click</td>
                    <td><input type="text" name="cpc" required placeholder="e.g: &euro;0.10" value="<?=$cpc?>" /></td>
                </tr>
                <tr>
                    <td>Clicks</td>
                    <td><input type="text" name="clicks" required placeholder="e.g: 100" value="<?=$clicks?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Opslaan!" /></td>
                </tr>
                </table>
                <?php
            }
            ?>
        </form>
    </div>
</section>