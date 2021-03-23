<?php

namespace app\controllers;

use app\Model;
use app\models\Comment;

class CommentController extends Model
{
    public function newcomment() {
        if (self::checkSession() === true) {
            $check = Comment::checkDataForNewComment();
            if ($check["result"] === false) {
                echo json_encode($check);
            } else {
                $result = Comment::createComment();
                if ($result["result"] === true) {
                    Comment::sendCommentNotification($result["comment_id"]);
                    echo json_encode(["result" => true, "message" => "Комментарий успешно добавлен"]);
                } else {
                    echo json_encode(["result" => false, "message" => "Не удалось добавить комментарий"]);
                }
            }
        }
    }

    public function getcomments() {
        $check = Comment::checkDataForGetComments();
        $photo_id = $_POST['photo_id'];
        if ($check["result"] === false) {
            echo json_encode($check);
        } else {
            $result = Comment::getComments($photo_id);
            echo json_encode($result);
        }
    }

    public function deletecomment() {
        if (self::checkSession() === true) {
            $check = Comment::checkDataForDeleteComment();
            $comment_id = $_POST['comment_id'];
            if ($check["result"] === false) {
                echo json_encode($check);
            } else {
                $result = Comment::deleteComment($comment_id);
                echo json_encode($result);
            }
        }
    }
}