<?php

namespace app\models;

use app\Model;
use Exception;
use PDOException;

class UserValidation extends Model
{
	public static function checkLoginIsSet()
	{
		if (isset($_POST['login'])) {
			return true;
		} else {
			return "Введите логин";
		}
	}

	public static function checkEmailIsSet()
	{
		if (isset($_POST['email'])) {
			return true;
		} else {
			return "Введите почту";
		}
	}

	public static function checkPasswordIsSet()
	{
		if (isset($_POST['password'])) {
			return true;
		} else {
			return "Введите пароль";
		}
	}

	public static function checkNameIsSet()
	{
		if (isset($_POST['name'])) {
			return true;
		} else {
			return "Введите имя";
		}
	}

	public static function checkSurnameIsSet()
	{
		if (isset($_POST['surname'])) {
			return true;
		} else {
			return "Введите фамилию";
		}
	}

    public static function checkLoginExist($login) {
        try {
            $link = self::getDB();
            $sql = "SELECT id FROM users WHERE login=:login";
            $sth = $link->prepare($sql);
            $sth->bindParam(':login', $login);
            $sth->execute();
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } catch( PDOException $e) {
            return $e->getMessage();
        } catch( Exception $e) {
            return $e->getMessage();
        }
        if ($result) {
            return $result;
        }
        return false;
    }

	public static function validateLogin()
	{
		if (($message = self::checkLoginIsSet()) !== true) {
			return $message;
		}
		if (strlen($_POST['login']) > 20) {
			return "Логин должен включать в себя не больше 20 символов";
		}
		if (!(preg_match("/^[a-z][a-z0-9]{1,20}$/", $_POST['login']))) {
			return "Некорректный логин. Логин может включать в себя только цифры 
					и маленькие латинские буквы.";
		}
        if (self::checkLoginExist($_POST['login']) !== false) {
            return "Такой login уже зарегестрирован";
        }

		return true;
	}

	public static function checkEmailExist($email)
	{
		try {
			$link = self::getDB();
			$sql = "SELECT id FROM users WHERE email=:email";
			$sth = $link->prepare($sql);
			$sth->bindParam(':email', $email);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
		} catch( PDOException $e) {
			return $e->getMessage();
		} catch( Exception $e) {
			return $e->getMessage();
		}
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public static function validateEmail()
	{
		if (($message = self::checkEmailIsSet()) !== true) {
			return $message;
		}
        if (strlen($_POST['email']) > 50) {
            return "E-mail должен включать в себя не больше 50 символов";
        }
		if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
			return "E-mail адрес указан неверно";
		}
		if (self::checkEmailExist($_POST['email']) !== false) {
			return "Такой e-mail уже зарегестрирован";
		}
		return true;
	}

	public static function validateNameSurname()
	{
		if (($message = self::checkNameIsSet()) !== true) {
			return $message;
		}
        if (strlen($_POST['name']) > 30) {
            return "Имя должно включать в себя не больше 30 символов";
        }
		if (!(preg_match("/^[A-Z]/", $_POST['name']))) {
			return "Имя должно начинаться с большой буквы.";
		}
		if (!(preg_match("/^[A-Z][a-z]{1,30}$/", $_POST['name']))) {
			return "Имя может включать в себя только латинские буквы";
		}
		if (($message = self::checkSurnameIsSet()) !== true) {
			return $message;
		}
        if (strlen($_POST['surname']) > 30) {
            return "Фамилия должна включать в себя не больше 30 символов";
        }
		if (!(preg_match("/^[A-Z]/", $_POST['name']))) {
			return "Фамилия должна начинаться с большой буквы.";
		}
		if (!(preg_match("/^[A-Z][a-z]{1,30}$/", $_POST['name']))) {
			return "Фамилия может включать в себя только латинские буквы";
		}
		return true;
	}

	public static function validatePassword()
	{
		if (($message = self::checkPasswordIsSet()) !== true) {
			return $message;
		}
		if (!isset($_POST['password2']))
			return "Повторите пароль";
		if ($_POST['password'] === $_POST['login'])
			return "Пароль не может совпадать с именем пользователя";
		if (strlen($_POST['password']) < 8)
			return "Пароль должен включать в себя не менее 8 символов";
		if (!(preg_match("/(?=.*[0-9])(?=.*[a-zA-Z])/", $_POST['password'])))
			return "Некорректный пароль. Пароль должен включать в себя как минимум одну цифру, одну маленькую и 
			одну большую латинскую букву";
		#todo потом исправить $pattern
		if ($_POST['password'] !== $_POST['password2'])
			return "Пароли не совпадают.";
		return true;
	}

	public static function validateUserCreation()
	{
		if (($message = self::validateLogin()) !== true) {
			return $message;
		}
		if (($message = self::validateEmail()) !== true) {
			return $message;
		}
		if (($message = self::validateNameSurname()) !== true) {
			return $message;
		}
		if (($message = self::validatePassword()) !== true) {
			return $message;
		}
		return true;
	}

    public static function validateUserUpdate()
    {
        if (($message = self::checkLoginIsSet()) !== true) {
            return $message;
        }
        if (strlen($_POST['login']) > 20) {
            return "Логин должен включать в себя не больше 20 символов";
        }
        if (!(preg_match("/^[a-z][a-z0-9]{1,20}$/", $_POST['login']))) {
            return "Некорректный логин. Логин может включать в себя только цифры 
					и маленькие латинские буквы.";
        }

        $login = self::checkLoginExist($_POST['login']);
        if ($login !== false && $login["id"]  !== $_SESSION["user_id"]) {
            return "Такой login уже зарегестрирован";
        }
        if (($message = self::checkEmailIsSet()) !== true) {
            return $message;
        }
        if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
            return "E-mail адрес указан неверно";
        }
        $email = self::checkEmailExist($_POST['email']);
        if ($email !== false && $email["id"] !== $_SESSION["user_id"]) {
            return "Такой e-mail уже зарегестрирован";
        }

        if (($message = self::validateNameSurname()) !== true) {
            return $message;
        }
        return true;
    }

	public static function validateAuth()
	{
		if (($message = self::checkLoginIsSet()) !== true) {
			return $message;
		}
		if (($message = self::checkPasswordIsSet()) !== true) {
			return $message;
		}
		return true;
	}

}