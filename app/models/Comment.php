<?php

namespace app\models;

use app\Model;
use Exception;
use PDOException;

class Comment extends Model
{
	public static function createComment() {
        $result = "";

		try {
			$created_at = date("Y-m-d H:i:s");
			$photo_id = $_POST['photo_id'];
			$user_id = $_POST['user_id'];
			$comment_text = $_POST['comment'];
			$link = self::getDB();
			$sql = "INSERT INTO comments (photo_id, user_id, comment_text, created_at)
			VALUES (:photo_id, :user_id, :comment_text, :created_at)";
			$sth = $link->prepare($sql);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':comment_text', $comment_text);
			$sth->bindParam(':created_at', $created_at);
			$sth->execute();
			$result = $link->lastInsertId();
		} catch( PDOException $e) {
			return $e->getMessage();
		} catch( Exception $e) {
			return $e->getMessage();
		}

		return ["result" => true, "comment_id" => $result];
	}

	public static function getComments($photo_id) {
		$error = "";
		$result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT comments.id, photo_id, user_id, comment_text, comments.created_at, users.login FROM comments 
					INNER JOIN users ON comments.user_id = users.id
					WHERE photo_id=:photo_id
					ORDER BY comments.created_at";
			$sth = $link->prepare($sql);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}

		return $result;
	}

	public static function getOneComment($comment_id) {
		$error = "";
        $result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT * FROM comments 
					WHERE id=:id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':id', $comment_id);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return false;
		}
		return $result;
	}

	public static function checkDataForNewComment() {

		if (!isset($_SESSION["user"]) && !isset($_SESSION["user_id"])) {
			return ["result" => false, "message" => "???????????????????????? ???? ??????????????????????????"];
		}
		if (!isset($_POST["comment"]) || $_POST["comment"] === "") {
			return ["result" => false, "message" => "?????????????????????? ?????????? ??????????????????????"];
		}
		if (!isset($_POST["photo_id"]) || $_POST["photo_id"] === "") {
			return ["result" => false, "message" => "?? ?????????????? ?????????????????????? id-????????????????????"];
		}
		if (!isset($_POST["user_id"]) || $_POST["user_id"] === "") {
			return ["result" => false, "message" => "?? ?????????????? ?????????????????????? id-????????????????????????"];
		}
		if ($_SESSION["user_id"] !== $_POST["user_id"]) {
			return ["result" => false, "message" => "???????????????????????? ???? ?????????????????????????? ?????????????????????????????????? ????????????????????????"];
		}
		if (!(Photo::getPhoto($_POST["photo_id"]))) {
			return ["result" => false, "message" => "???????? ???? ??????????????"];
		}
		if (!(User::getUserLogin($_POST["user_id"]))) {
			return ["result" => false, "message" => "???????????????????????? ???? ????????????"];
		}

		return ["result" => true];
	}

	public static function checkDataForGetComments() {
		if (!isset($_POST["photo_id"]) || $_POST["photo_id"] === "") {
			return ["result" => false, "message" => "?? ?????????????? ?????????????????????? id-????????????????????"];
		}
		if (!(Photo::getPhoto($_POST["photo_id"]))) {
			return ["result" => false, "message" => "???????? ???? ??????????????"];
		}
		return ["result" => true];
	}

	public static function checkDataForDeleteComment() {
        if (!isset($_POST["comment_id"]) || $_POST["comment_id"] === "") {
            return ["result" => false, "message" => "?? ?????????????? ?????????????????????? id-??????????????????????"];
        }
        $comment = Comment::getOneComment($_POST["comment_id"]);
        if (!$comment) {
            return ["result" => false, "message" => "?????????????????????? ???? ????????????"];
        }
        if ($comment['user_id'] !== $_SESSION["user_id"]) {
            return ["result" => false, "message" => "???? ???? ?????????????????? ???????????????????? ??????????????????????"];
        }
		return ["result" => true];
	}

	public static function deleteComment($comment_id) {
		try {
			$link = self::getDB();
			$sql = "DELETE FROM comments WHERE id=:id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':id', $comment_id);
			$sth->execute();
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return ["result" => "?????????????????? ???????????? ?????? ???????????????? ??????????????????????"];
		}
		return ["result" => true];
	}

	public static function deleteAllCommentsPhoto($photo_id) {
		$error = "";
		try {
			$link = self::getDB();
			$sql = "DELETE FROM comments WHERE photo_id=:photo_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':photo_id', $photo_id);
			$sth->execute();
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return ["result" => "?????????????????? ???????????? ?????? ???????????????? ????????????????????????"];
		}
		return ["result" => true];
	}

	public static function sendCommentNotification($comment_id)
	{
		$commentData = Comment::getOneComment($comment_id);
		$recipientId = Photo::getPhotoOwner($commentData["photo_id"]);
		$recipientData = User::getRecipientInformation($recipientId["user_id"]);
		$commentator = User::getUserLogin($commentData["user_id"]);

		if ($recipientData["notification"] === "1") {
			$subject = "Camagru - ?????????? ??????????????????????";

			$message = "???????????????????????? " . $commentator["login"] . " ?????????????? ?????? ?????????? ?????????????????????? ?????? ?????????????????????? " .
				self::ADDRESS . "/photo/" . $commentData["photo_id"];

			if (self::sendMail($recipientData['email'], $subject, $message) === false) {
				return "?????????????????? ???????????? ?????? ???????????????? ????????????";
			}
		}
		return true;
	}

}