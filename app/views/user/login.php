<html>
<?php require_once "app/views/header.php" ?>
<body>
<?php require_once "app/views/navbar.php" ?>
<div class="container">
<h1 class="display-4">Вход</h1>
<?php
if ($message !== "") {
	echo "<p>" . htmlspecialchars($message) . "</p>";
	$message = "";
}
?>
    <div class="container">
        <div class="jumbotron">
            <div class="login-form">
                <form method="post" action="">
                    Логин:<br>
                    <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <input type="text" name="login" required><br>
                    Пароль:<br>
                    <svg class="bi bi-lock" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 00-1 1v5a1 1 0 001 1h7a1 1 0 001-1V9a1 1 0 00-1-1zm-7-1a2 2 0 00-2 2v5a2 2 0 002 2h7a2 2 0 002-2V9a2 2 0 00-2-2h-7zm0-3a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                    </svg>
                    <input type="password" name="password" required><br>
                    <br><input class="btn btn-primary" type="submit" name="submit" value="Войти"><br>
                    <br><h6>Ещё не зарегистрированы? <a href="registration">Регистрация</a></h6>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<?php require_once "app/views/footer.php" ?>
</html>

