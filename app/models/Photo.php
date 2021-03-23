<?php

namespace app\models;
use app\Model;
use Exception;
use PDOException;

class Photo extends Model
{
	public static function getBase64() {
		if (isset($_FILES['image'])) {
			$image = $_FILES['image'];
			$fileTmpName = $image['tmp_name'];
			$errorCode = $image['error'];
			if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmpName)) {
				$errorMessages = [
					UPLOAD_ERR_INI_SIZE => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
					UPLOAD_ERR_FORM_SIZE => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
					UPLOAD_ERR_PARTIAL => 'Загружаемый файл был получен только частично.',
					UPLOAD_ERR_NO_FILE => 'Файл не был загружен.',
					UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
					UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
					UPLOAD_ERR_EXTENSION => 'PHP-расширение остановило загрузку файла.',
				];
				$unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
				$outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
				return (['error' => $outputMessage]);
			}
			$fi = finfo_open(FILEINFO_MIME_TYPE); // Создадим ресурс FileInfo
			$mime = (string)finfo_file($fi, $fileTmpName); // Получим MIME-тип
			if (strpos($mime, 'image') === false) {
				return (['error' => "Можно загружать только изображения."]);
			}
			$data = file_get_contents($fileTmpName);
			$result = "data:image/png;base64," . base64_encode($data);
			return (['photo' => $result]);
		}
        return (['error' => "Фото не выбрано"]);
	}

	public static function PhotoValidation($photo) {
		$size_info = getimagesize($photo);
		if ($size_info) {
			return $size_info;
		} else {
			return ["error" => "Некорректный файл"];
		}
	}

	public static function saveToDB($photo, $description, $user_id) {
		$created_at = date("Y-m-d H:i:s");
		try {
			$link = self::getDB();
			$sql = "INSERT INTO photos (user_id, description, created_at, photo)
			VALUES (:user_id, :description, :created_at, :photo)";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':description', $description);
			$sth->bindParam(':photo', $photo);
			$sth->bindParam(':created_at', $created_at);
			$sth->execute();
		} catch(PDOException $e) {
			return $e->getMessage();
		} catch(Exception $e) {
			return $e->getMessage();
		}
		return true;
	}

	private static function getStartingPhotoNumber($photoCount) {
		$pageCount = ceil($photoCount / 6);
		$from = 0;
		if ($photoCount === 0) {
			return ["error" => "В галерее пока нет фотографий"];
		}
		if (isset($_GET['page'])) {
			$pageNumber = $_GET['page'];
			if (!ctype_digit($pageNumber)) {
				return ["error" => "Неправильный запрос"];
			}
			if ($pageNumber >= 1 && $pageNumber <= $pageCount) {
				$from += 5 * ($pageNumber - 1);
				return [ "from" => $from,
						"pageCount" => (int)$pageCount,
						"pageNumber" => (int)$pageNumber];
			}
			else {
				return ["error" => "Неправильный запрос"];
			}
		} else {
			return [ "from" => 0,
				"pageCount" => (int)$pageCount,
				"pageNumber" => 1];
		}
	}

	public static function getGallery() {
		$photoCount = self::getPhotoCount();
		$data = self::getStartingPhotoNumber($photoCount);
		$error = "";
		$result = "";
		if (isset($data["error"])) {
			return ["error" => $data["error"]];
		}
		try {
			$link = self::getDB();
			$sql = "SELECT photos.id, photos.photo, users.login FROM photos
    				INNER JOIN users ON photos.user_id = users.id
					ORDER BY photos.created_at DESC 
					LIMIT " . $data["from"] . ", 6";
			$sth = $link->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return ["error" => "Произошла ошибка при подключение к БД"];
		}
		return ["photos" => $result,
				"pageCount" => $data["pageCount"],
				"pageNumber" => $data["pageNumber"]];
	}

	public static function getRandomPhotos() {
	    $result = "";

        try {
            $link = self::getDB();
            $sql = "SELECT photos.id, photos.photo, users.login FROM photos
    				INNER JOIN users ON photos.user_id = users.id
					ORDER BY rand() 
					LIMIT 10";
            $sth = $link->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch( PDOException $e) {
            $error = $e->getMessage();
        } catch( Exception $e) {
            $error = $e->getMessage();
        }

        return ["photos" => $result];
    }

	public static function getUserPhotos($user_id) {
		$photoCount = self::getUserPhotoCount($user_id);
		$data = self::getStartingPhotoNumber($photoCount);
		$error = "";
		if (isset($data["error"])) {
			return ["error" => $data["error"]];
		}
		try {
			$link = self::getDB();
			$sql = "SELECT id, photo FROM photos
					WHERE user_id=:user_id
					ORDER BY created_at DESC 
					LIMIT " . $data["from"] . ", 6";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			$error = $e->getMessage();
		} catch( Exception $e) {
			$error = $e->getMessage();
		}
		if ($error) {
			return ["error" => "Произошла ошибка при подключение к БД"];
		}
		return ["photos" => $result,
				"pageCount" => $data["pageCount"],
				"pageNumber" => $data["pageNumber"]];
	}

	public static function getPhotoOwner($photo_id) {
		$error = "";
		$result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT user_id FROM photos WHERE id=:id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':id', $photo_id);
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

	public static function getPhoto($photo_id) {
		$error = "";
		$result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT id, user_id, photo, description, created_at FROM photos WHERE id=:id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':id', $photo_id);
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

	public static function getPhotoCount() {
		$error = "";
		$result = "";

		try {
			$link = self::getDB();
			$sql = "SELECT id FROM photos";
			$sth = $link->prepare($sql);
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
		return $result;
	}

	public static function getUserPhotoCount($user_id) {
		$error = "";
		$result = "";
		try {
			$link = self::getDB();
			$sql = "SELECT id FROM photos WHERE user_id=:user_id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
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
		return $result;
	}

	public static function deletePhoto($photo_id, $user_id) {
		$error = "";
		if ($_SESSION['user_id'] !== $user_id) {
			return false;
		}
		try {
			$link = self::getDB();
			$sql = "DELETE FROM photos WHERE id=:id";
			$sth = $link->prepare($sql);
			$sth->bindParam(':id', $photo_id);
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

	public static function mergePhotos($photo, $mask) {

        $image = str_replace('data:image/png;base64,', '', $photo);
        $filter = str_replace('data:image/png;base64,', '', $mask);

        $image = base64_decode($image);
        $filter = base64_decode($filter);

		$image = imagecreatefromstring($image);
		$filter = imagecreatefromstring($filter);

		imagealphablending($filter, false);
		imagesavealpha($filter, true);

        $w_src = imagesx($image);
        $h_src = imagesy($image);

        imagecopyresampled($image, $filter, 0, 0, 0, 0,  $w_src, $h_src, $w_src, $h_src );
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $image_name = "tmp/tmp_" . $_SESSION["user_id"] . ".png";
        imagepng($image, $image_name);
        $data = file_get_contents($image_name);
        unlink($image_name);
        return "data:image/png;base64," . base64_encode($data);
	}

}