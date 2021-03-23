<?php

namespace app;
use PDO;
use PDOException;

class Model
{
	const ADDRESS = "http://localhost:8888";

    protected static function checkSession() {
        if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== "" &&
            isset($_SESSION["user"]) && $_SESSION["user"] !== "") {
            self::setCookie();
            return true;
        }
        return false;
    }

    protected static function setCookie() {
        setcookie('user', $_SESSION["user"]);
        setcookie('user_id', $_SESSION["user_id"]);
    }

	protected static function getDB()
    {
    	try {
    		require('config/database.php');
    		$db_connection = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    		$db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	} catch (PDOException $e) {
    		echo("ERROR: " . $e->getMessage());
    	}
        return $db_connection;
    }

    protected static function sendMail($to, $subject, $message)
	{
		date_default_timezone_set('Europe/Moscow');
		ini_set("SMTP", "127.0.0.1");
		ini_set("smtp_port", "25");
		$message = wordwrap($message, 70, "\r\n");
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "Content-Transfer-Encoding: utf-8\r\n";
		$headers .= "Reply-To: no-reply@gmail.com\r\n";
		$result = mail($to, $subject, $message, $headers);
		return $result;
	}

    protected static function ErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }

    protected static function debug($str)
    {
        echo '<pre>';
        var_dump($str);
        echo '</pre>';
    }

}
