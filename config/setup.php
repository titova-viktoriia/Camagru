<?php

//require_once 'database.php';
$DB_USER = 'root';
$DB_PASSWORD = 'test';
$db = new PDO('mysql:host=127.0.0.1', $DB_USER, $DB_PASSWORD);
$sql = file_get_contents('database.sql');
$query = $db->exec($sql);

//создание админа
$password = hash('whirlpool', "admin1");
$created_at = $today = date("Y-m-d H:i:s");
$sql = "INSERT INTO users (login, name, surname, password, email, token, activated, created_at)
			VALUES ('admin', 'Admin', 'Admin', :password, 'admin@admin.ru', '-', 1, :created_at)";
$sth = $db->prepare($sql);
$sth->bindParam(':password', $password);
$sth->bindParam(':created_at', $created_at);
$sth->execute();

