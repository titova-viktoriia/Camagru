<?php

namespace app\controllers;

use app\models\User;
use app\models\Notification;
use app\Router;

class UserController extends Router
{
	private $DIR_PATH = 'app/views/user/';

	public function registration()
	{
        if (self::checkSession() === true) {
            header('Location: /user/' . $_SESSION['user']);
        }
		$message = "";
		if (isset($_POST['submit'])) {
			$result = User::createUser();
			if ($result === true) {
				$message = "Ваш аккаунт создан, для его активации 
				необходимо перейти по ссылке, направленной Вам на почту";
			} else {
				$message = $result;
			}
		}
		$pathView = $this->DIR_PATH . 'registration.php';
		require_once $pathView;
	}

	public function login()
	{
        if (self::checkSession() === true) {
            header('Location: /user/' . $_SESSION['user']);
        }
		$message = "";
		if (isset($_POST['submit']))
		{
			$result = User::login();
			if ($result === true)
				header('Location: /user/' . $_SESSION['user']);
			else
				$message = $result;
		}
		$pathView = $this->DIR_PATH . 'login.php';
		require_once $pathView;
	}

	public function user($user)
	{
		$user = User::getUser($user);
		if ($user)
		{
			$pathView = $this->DIR_PATH . 'useraccount.php';
			require_once $pathView;
		}
		else {
			self::ErrorPage404();
		}
	}

	public function logout()
	{
		$_SESSION['user'] = "";
		$_SESSION['user_id'] = "";
		setcookie('user', "", time()-3600, "/");
		setcookie('user_id', "", time()-3600, "/");
		header('Location: /');
	}

	public function token()
	{
        if (self::checkSession() === true) {
            header('Location: /user/' . $_SESSION['user']);
        }
		$message = "";
		if (isset($_GET['token']) && isset($_GET['id']))
		{
			$token = $_GET['token'];
			$id = $_GET['id'];
			$result = User::activateAccount($id, $token);
			if ($result === true) {
				$message = "Аккаунт активирован";
			} else {
				$message = $result;
			}
			$pathView = $this->DIR_PATH . 'token.php';
			require_once $pathView;
		} else {
			$pathView = 'app/views/index.php';
			require_once $pathView;
		}
	}

	public function recovery()
	{
        if (self::checkSession() === true) {
            header('Location: /user/' . $_SESSION['user']);
        }
		$message = "";
		if (isset($_POST['submit']))
		{
			$result = User::sendRecoveryLink();
			if ($result === true) {
				$message = "Ccылка на восстановление пароля отправлена Вам на почту";
			} else {
				$message = $result;
			}
		}
		$pathView = $this->DIR_PATH . 'recovery.php';
		require_once $pathView;
	}

	public function changepassword()
	{
        if (self::checkSession() === true) {
            header('Location: /user/' . $_SESSION['user']);
        }
		$message = "";
		if (isset($_GET['token'])) {
			$result = User::recoveryLinkConfirmation();
			if (isset($result['user_id'])) {
				if (isset($_POST['submit'])) {
					$changeResult = User::changePassword($result['user_id']);
					User::deleteFromResetPassword($result['user_id']);
					$message = $changeResult;
				}
			} else {
				$message = $result;
			}
			$pathView = $this->DIR_PATH . 'changepassword.php';
			require_once $pathView;
		} else {
			self::ErrorPage404();
		}
	}

	public function settings() {
        if (self::checkSession() === true) {
            $pathView = $this->DIR_PATH . 'settings.php';
            $user = User::getUser($_SESSION['user']);
            if (isset($_POST['submit_password'])) {
                $changeResult = User::changePassword($_SESSION['user_id']);
                $message = $changeResult;
            }
            if (isset($_POST['submit_userinfo'])) {
                $message = User::changeUserInfo();
            }
            if (isset($_POST['submit_deletephoto'])) {
                $message = User::changeUserAvatar(null);
            }
            if (isset($_POST['submit_updatephoto'])) {
                $message = User::updateUserAvatar();
            }
            require_once $pathView;
        } else {
            header('Location: /');
        }
	}

	public function checknotification()
    {
        if (self::checkSession() === true) {
            $result = Notification::checkNotification($_SESSION['user_id']);
            echo json_encode($result);
        }
    }

	public function changenotification()
    {
        if (self::checkSession() === true) {
            $result = Notification::checkNotification($_SESSION['user_id']);
            if ($result["result"]) {
                Notification::changeNotification($_SESSION['user_id'], '0');
                $message["message"] = "Уведомления отключены";
            } else {
                Notification::changeNotification($_SESSION['user_id'], '1');
                $message["message"] = "Уведомления подключены";
            }
            echo json_encode($message);
        }
    }

}
