<?php

define("SERVER", "localhost");
define("USER", "root");
define("PASS", "usbw");
define("DB_NAME", "userdb_33377");

$con = new mysqli(SERVER, USER, PASS, DB_NAME);

if (!$con) {
    echo "<h3>Database connection error</h3>";
    die();
}