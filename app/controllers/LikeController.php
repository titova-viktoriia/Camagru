<?php

namespace app\controllers;

use app\Model;
use app\models\Like;

class LikeController extends Model
{
    public function getlikes() {
        $check = Like::checkDataForLikesCount();
        $photo_id = $_POST['photo_id'];
        if ($check["result"] === false){
            echo json_encode($check);
        } else {
            $result = Like::getLikesCount($photo_id);
            if (isset($_SESSION["user"]) && isset($_SESSION["user_id"])) {
                $result["usersLike"] = Like::checkIfLikeIs($photo_id, $_SESSION["user_id"]);
            } else {
                $result["usersLike"] = false;
            }
            echo json_encode($result);
        }
    }

    public function changelike() {
        if (self::checkSession() === true) {
            $check = Like::checkDataForLikesChanging();
            if ($check["result"] === false){
                echo json_encode($check);
            } else {
                $photo_id = $_POST['photo_id'];
                $user_id = $_POST['user_id'];
                $result = Like::checkIfLikeIs($photo_id, $user_id);
                if ($result === true) {
                    Like::deleteLike($photo_id, $user_id);
                    $result = ["message" => "Лайк удален"];
                } else {
                    Like::newLike($photo_id, $user_id);
                    $result = ["message" => "Лайк добавлен"];
                }
                echo json_encode($result);
            }
        }
    }
}