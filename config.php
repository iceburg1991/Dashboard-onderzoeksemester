<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dylan
 * Date: 1-5-13
 * Time: 13:24
 * To change this template use File | Settings | File Templates.
 */

// Google Console Credentials
define('ACCESS_TYPE',       'online');
define('APPLICATION_NAME',  'Dashboard proof of concept');
define('CLIENT_ID',         '814565602276.apps.googleusercontent.com');
define('CLIENT_SECRET',     'EkMosZD60pI5At0yATGtgp7M');
define('REDIRECT_URI',      'http://' . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF']);
define('DEVELOPER_KEY',     'AIzaSyBiyh3JZfDQ_ZFnx0hSKAayNG2IKzfRi4U');

// Magento API settings
define('API_USER',          'Hugo');
define('API_KEY',           'Ka0yoiAOJhoifqap0oinhlkqfn0oe8vh0234gtQ965WGEWROIUHJWEROIGNRESD98OHL234TWP98YWERFGNLKAERGO87HKJN234TPHJKZWGRHLI');
define('API_HOST',          'http://magento.presteren.nu/api/soap/?wsdl');

//Database settings
define('DB_HOST',           'localhost');
define('DB_NAME',           'ocrtxndf_dash');
define('DB_USERNAME',       'ocrtxndf_dashboard');
define('DB_PASS',           'ocrtxndf_dashboard');
?>