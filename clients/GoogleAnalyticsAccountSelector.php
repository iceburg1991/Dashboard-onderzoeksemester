<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 08-05-13
 * Time: 17:46
 * To change this template use File | Settings | File Templates.
 */

// Account selector
$GoogleAnalyticsAccountSelector = new GoogleAnalyticsAccountSelector($service);

if ((isset($_GET['propertyId']) && !empty($_GET['propertyId'])) && (isset($_GET['accountId']) && !empty($_GET['accountId'])) && (isset($_GET['profileId']) && !empty($_GET['profileId']))) {
    // Parse the $_GET vars
    $propertyId = $_GET['propertyId'];
    $accountId = $_GET['accountId'];
    $profileId = $_GET['profileId'];

    // Gets the list of profiles attached to the account
    $GoogleAnalyticsAccountList = $GoogleAnalyticsAccountSelector->listProfiles($propertyId, $accountId);

    // Since we have a propertyId and accountId we know that there is only 1 account, so we can take the first object
    // from the array and use it as object.
    $GoogleAnalyticsAccount = $GoogleAnalyticsAccountList[key($GoogleAnalyticsAccountList)];
    $GoogleAnalyticsProperty = $GoogleAnalyticsAccount->getPropertyById($propertyId);
    $GoogleAnalyticsProfile = $GoogleAnalyticsProperty->getProfileById($profileId);

    ?>
    <section class="onerow full color3 tagline taglineimg">
        <div class="inner">
            <img src="http://www.presteren.nu/wp-content/uploads/static/tagline/website/pano-esser-emmerik.jpg"
                 alt="Mannen aan het werk!">

            <div class="caption-bg" style="height: 80px;"></div>
            <div class="onepcssgrid-1200">
                <div class="col12">
                    <div
                        class="caption"><?php echo $GoogleAnalyticsAccount->getName() . ' - ' . $GoogleAnalyticsProfile->getName(); ?></div>
                </div>
            </div>
            <div class="arrow" style="left: 106.57359313964844px; "></div>
        </div>
    </section>
<?php
} else {
    ?>
    <section class="onerow full color3 tagline taglineimg">
        <div class="inner">
            <img src="http://www.presteren.nu/wp-content/uploads/static/tagline/website/pano-esser-emmerik.jpg"
                 alt="Mannen aan het werk!">

            <div class="caption-bg" style="height: 80px;"></div>
            <div class="onepcssgrid-1200">
                <div class="col12">
                    <div class="caption">Selecteer een account, property en profile</div>
                </div>
            </div>
            <div class="arrow" style="left: 106.57359313964844px; "></div>
        </div>
    </section>
    <section class="onerow full color1">
        <div class="onepcssgrid-1200">
            <?php

            // Accounts listen
            $GoogleAnalyticsAccountSelector->listAllProfiles();

            // Used to switch CSS styles
            $counter = 0;

            if ($GoogleAnalyticsAccountSelector->hasGoogleAnalyticsAccounts()) {
                foreach ($GoogleAnalyticsAccountSelector->getGoogleAnalyticsAccounts() as $account) {
                    if ($counter % 2 == 0) {
                        echo '<div class="col6">';
                    } else {
                        echo '<div class="col6 last">';
                    }
                    echo '<h2 class="ic">' . $account->getName() . '</h2>';
                    $properties = $account->getProperties();
                    foreach ($properties as $property) {
                        $profiles = $property->getProfiles();
                        echo '<p>';
                        foreach ($profiles as $profile) {
                            echo '<a href="index.php?accountId=' . $account->getAccountId() . '&profileId=' . $profile->getProfileId() . '&propertyId=' . $property->getWebPropertyId() . '">' . $profile->getName() . '</a><br />';
                        }
                        echo '</p>';
                    }
                    echo '<p></p></div>';
                    $counter++;
                }
            } else {
                echo 'Geen accounts gevonden. Maak eerst een Google Analytics Account aan.';
            }

            ?>
        </div>
    </section>
<?php
}

