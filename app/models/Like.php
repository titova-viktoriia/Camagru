<?php

namespace app\models;

use app\Model;
use Exception;
use PDOException;

class Like extends Model
{
	public static function checkIfLikeIs($photo_id, $user_id) {
		$error = "";
		$result = "";
		try {
			$link = self::getDB();
			$sql = "SELECT id FROM likes
					WHERE user_id=:user_id AND photo_id=:photo_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
			$result = $sth->rowCount(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public static function newLike($photo_id, $user_id) {
		$created_at = date("Y-m-d H:i:s");
		try {
			$link = self::getDB();
			$sql = "INSERT INTO likes (user_id, photo_id, created_at)
			VALUES (:user_id, :photo_id, :created_at)";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->bindParam(':created_at', $created_at);
			$sth->execute();
		} catch(PDOException $e) {
			return $e->getMessage();
		} catch(Exception $e) {
			return $e->getMessage();
		}
		return true;
	}

	public static function getLikesCount($photo_id) {
		$error = "";
        $result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT id FROM likes
					WHERE photo_id=:photo_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
			$result = $sth->rowCount(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}
		return ['likesCount' => $result];
	}

	public static function deleteLike($photo_id, $user_id) {
		$error = "";

	    try {
			$link = self::getDB();
			$sql = "DELETE FROM likes
			WHERE user_id=:user_id AND photo_id=:photo_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}
		return true;
	}

	public static function deleteAllLikesPhoto($photo_id) {
		$error = "";

	    try {
			$link = self::getDB();
			$sql = "DELETE FROM likes
			WHERE photo_id=:photo_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}
		return true;
	}

	public static function checkDataForLikesChanging() {
		if (!isset($_SESSION["user"]) && !isset($_SESSION["user_id"])) {
			return ["result" => false, "message" => "Пользователь не авторизирован"];
		}
		if (!isset($_POST["photo_id"]) || $_POST["photo_id"] === "") {
			return ["result" => false, "message" => "В запросе отсутствует id-фотографии"];
		}
		if (!isset($_POST["user_id"]) || $_POST["user_id"] === "") {
			return ["result" => false, "message" => "В запросе отсутствует id-пользователя"];
		}
		if ($_SESSION["user_id"] !== $_POST["user_id"]) {
			return ["result" => false, "message" => "Пользователь не соответствует авторизированному пользователю"];
		}
		if (!(Photo::getPhoto($_POST["photo_id"]))) {
			return ["result" => false, "message" => "Фото не найдено"];
		}
		if (!(User::getUserLogin($_POST["user_id"]))) {
			return ["result" => false, "message" => "Пользователь не найден"];
		}
		return ["result" => true];
	}

	public static function checkDataForLikesCount() {
		if (!isset($_POST["photo_id"]) || $_POST["photo_id"] === "") {
			return ["result" => false, "message" => "В запросе отсутствует id-фотографии"];
		}
		if (!(Photo::getPhoto($_POST["photo_id"]))) {
			return ["result" => false, "message" => "Фото не найдено"];
		}
		return ["result" => true];
	}

}