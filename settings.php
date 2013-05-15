<?php
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

// When data gets posted.
if (isset($_POST['submit']))
{
    $costs = $_POST['cost'];

    // TODO: validatie
    $google_analytics_account_id = $_POST['google_analytics_account_id'];
    $google_analytics_property_id = $_POST['google_analytics_property_id'];
    $google_analytics_profile_id = $_POST['google_analytics_profile_id'];

    $magento_api_url = $_POST['magento_api_url'];
    $magento_api_username = $_POST['magento_api_username'];
    $magento_api_key = $_POST['magento_api_key'];

    $settings->costs = $costs;

    $settings->google_analytics_account_id = $google_analytics_account_id;
    $settings->google_analytics_profile_id = $google_analytics_profile_id;
    $settings->google_analytics_property_id = $google_analytics_property_id;

    $settings->magento_api_url = $magento_api_url;
    $settings->magento_api_username = $magento_api_username;
    $settings->magento_api_key = $magento_api_key;

    R::store($settings, 1);
}

?>
    <section class="onerow full color1">
        <div class="onepcssgrid-1200">
            <form name="settings" method="post" action="settings.php">
            <div class="col6" style="border-right: 1px #000 solid">
                <table width="80%">
                    <tr>
                        <td colspan="2"><h2>Instellingen</h2></td>
                    </tr>
                    <tr>
                        <td>Vaste lasten per maand:</td>
                        <td><input type="text" name="cost" required placeholder="e.g: &euro;5000" value="<?php echo $settings->costs; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Google Analytics account</td>
                        <td><input type="text" name="google_analytics_account_id" required="true" placeholder="e.g: 40165459" value="<?php echo $settings->google_analytics_account_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Google Analytics profile</td>
                        <td><input type="text" name="google_analytics_profile_id" required="true" placeholder="eg: 71750844" value="<?php echo $settings->google_analytics_profile_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Google Analytics property</td>
                        <td><input type="text" name="google_analytics_property_id" required="true" placeholder="eg: UA-40165459-1" value="<?php echo $settings->google_analytics_property_id; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Google refresh token</td>
                        <td><input type="text" name="google_analytics_refresh_token" required="true" value="<?php echo $settings->google_analytics_refresh_token; ?>" disabled="true" /></td>
                    </tr>
                </table>
            </div>
            <div class="col6 last">
                <table width="80%">
                    <tr>
                        <td colspan="2"><h2>Magento instellingen</h2></td>
                    </tr>
                    <tr>
                        <td>Magento API url</td>
                        <td><input type="text" name="magento_api_url" required="true" placeholder="e.g: http://magento.presteren.nu/api/soap/?wsdl" value="<?php echo $settings->magento_api_url; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Magento API username</td>
                        <td><input type="text" name="magento_api_username" required="true" placeholder="e.g: admin" value="<?php echo $settings->magento_api_username; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Magento API key</td>
                        <td><input type="text" name="magento_api_key" required="true" placeholder="e.g: jEFHUGUIwrghew7y89ghiuo2goW9UP045" value="<?php echo $settings->magento_api_key; ?>" /></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="onepcssgrid-1200">
            <input type="submit" name="submit" value="Opslaan!" />
        </div>
        </form>
    </section>
</body>
</html>