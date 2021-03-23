<?php

namespace app\models;

use app\Model;
use Exception;
use PDOException;

class Notification extends Model
{
    public static function checkNotification($user_id) {
        try {
            $link = self::getDB();
            $sql = "SELECT notification FROM users WHERE id=:id";
            $sth = $link->prepare($sql);
            $sth->bindParam(':id', $user_id);
            $sth->execute();
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } catch( PDOException $e) {
            return $e->getMessage();
        } catch( Exception $e) {
            return $e->getMessage();
        }
        if ($result["notification"] === '1') {
            return ["result" => true];
        } else {
            return ["result" => false];
        }
    }

    public static function changeNotification($user_id, $notification) {
        try {
            $link = self::getDB();
            $sql = "UPDATE users SET notification=:notification WHERE id=:id";
            $sth = $link->prepare($sql);
            $sth->bindParam(':id', $user_id);
            $sth->bindParam(':notification', $notification);
            $sth->execute();
        } catch( PDOException $e) {
            return $e->getMessage();
        } catch( Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

}