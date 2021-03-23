<?php

namespace app\controllers;

use app\Model;
use app\models\Photo;
use app\models\User;
use app\models\Comment;
use app\models\Like;

class PhotoController extends Model
{
    private string $DIR_PATH = 'app/views/photo/';

	public function gallery()
	{
		$result = Photo::getGallery();
		require_once 'app/views/index.php';
	}

	public function photo()
    {
        if (self::checkSession() === true) {
            $result = Photo::getRandomPhotos();
            require_once 'app/views/photo/photo.php';
        } else {
            header('Location: / ');
        }
    }

	public function getOnePhoto($photo_id)
	{
		if (isset($_POST['delete']) && isset($_POST['photo_id']) && isset($_POST['user_id'])) {
			Like::deleteAllLikesPhoto($_POST['photo_id']);
			Comment::deleteAllCommentsPhoto($_POST['photo_id']);
			$result = Photo::deletePhoto($_POST['photo_id'], $_POST['user_id']);
			if ($result) {
				header('Location: /user/' . $_SESSION['user']);
			}
		} else {
			$photo = Photo::getPhoto($photo_id);
			if ($photo) {
				$login = User::getUserLogin($photo['user_id']);
				if ($login) {
					$photo['login'] = $login['login'];
				}
				require_once $this->DIR_PATH . 'onephoto.php';
			} else {
				self::ErrorPage404();
			}
		}
	}

    public function save()
    {
        if (self::checkSession() === true) {
            if (isset($_POST['photo'])) {
                $result = Photo::PhotoValidation($_POST['photo']);
                if (isset($result['error'])) {
                    echo $result['error'];
                } else {
                    $photo = Photo::mergePhotos($_POST['photo'], $_POST['masks']);
                    $description = $_POST['description'];
                    Photo::saveToDB($photo, $description, $_SESSION['user_id']);
                }
            }
        }
    }
}