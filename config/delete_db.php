<?php

require_once 'database.php';
$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$query = $db->exec("DROP SCHEMA IF EXISTS `camagru`");
echo $query;