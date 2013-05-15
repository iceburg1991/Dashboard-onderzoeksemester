<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijsbrand
 * Date: 15-5-13
 * Time: 1:26
 * To change this template use File | Settings | File Templates.
 */

 $mysqli = new mysqli("localhost", "user", "password", "database");
 if ($mysqli->connect_errno) {
     echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
 }
 echo $mysqli->host_info . "\n";

 $mysqli = new mysqli("127.0.0.1", "user", "password", "database", 3306);
 if ($mysqli->connect_errno) {
     echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
 }

 echo $mysqli->host_info . "\n";
 ?>