<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dylan
 * Date: 1-5-13
 * Time: 13:24
 * To change this template use File | Settings | File Templates.
 */

// Google Console Credentials
define('ACCESS_TYPE', 'offline');
define('APPLICATION_NAME', 'Dashboard proof of concept');
define('CLIENT_ID', '814565602276.apps.googleusercontent.com');
define('CLIENT_SECRET', 'EkMosZD60pI5At0yATGtgp7M');
define('REDIRECT_URI', 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF']);
define('DEVELOPER_KEY', 'AIzaSyBiyh3JZfDQ_ZFnx0hSKAayNG2IKzfRi4U');

//Database settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'ocrtxndf_dashboard');
define('DB_USERNAME', 'ocrtxndf_dash');
define('DB_PASS', '2ZjQCuTR');
?>