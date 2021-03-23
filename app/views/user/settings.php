<html>
<?php require_once "app/views/header.php" ?>
<body>
<?php require_once "app/views/navbar.php" ?>
<script src="/public/js/show_notification.js"></script>
<div class="container">
    <h1 class="display-4"> Настройки </h1>
    <div class="container">
        <div class="jumbotron" id="notification">
            <?php
                if ($message !== "") {
	            echo "<p>" . htmlspecialchars($message) . "</p>";
	            $message = "";
            } ?>
            <button id="notificationButton" class="btn btn-outline-secondary"></button>
            <br><br>
            <form name="newpassword" method="post" action="" >
                Новый пароль:<br>
                <svg class="bi bi-lock" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 00-1 1v5a1 1 0 001 1h7a1 1 0 001-1V9a1 1 0 00-1-1zm-7-1a2 2 0 00-2 2v5a2 2 0 002 2h7a2 2 0 002-2V9a2 2 0 00-2-2h-7zm0-3a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                </svg>
                <input type="password" name="password" min="4" placeholder=" At least 4 characters" required><br>
                Повторите пароль:<br>
                <svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <rect width="11" height="9" x="2.5" y="7" rx="2"/>
                    <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
                </svg>
                <input type="password" name="password2"  required><br>
                <br><input class="btn btn-primary" type="submit" name="submit_password" value="Сменить пароль"><br>
            </form>
            <form name="change_userinfo" method="post" action="" enctype="multipart/form-data">
                Логин:<br>
                <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <input type="text" name="login" value="<?php echo htmlspecialchars($user['login']);?>" max="15" required><br>
                E-mail:<br>
                <svg class="bi bi-at" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z" clip-rule="evenodd"/>
                </svg>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']);?>" required><br>
                Имя:<br>
                <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']);?>" max="30" required><br>
                Фамилия:<br>
                <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <input type="text" name="surname" value="<?php echo htmlspecialchars($user['surname']);?>" max="30" required><br>
                <br><input class="btn btn-primary" type="submit" name="submit_userinfo" value="Изменить данные"><br>
            </form>
            <form name="delete_photo" method="post" action="" >
                <br><input class="btn btn-primary" type="submit" name="submit_deletephoto" value="Удалить фото"><br>
            </form>
            <form name="update_photo" method="post" action="" enctype="multipart/form-data" >
                <input type="file" name="image">
                <br><input class="btn btn-primary" type="submit" name="submit_updatephoto" value="Изменить фото"><br>
            </form>
        </div>
    </div>
</div>
<script src="/public/js/change_notification.js"></script>
</body>
<?php require_once "app/views/footer.php" ?>
</html>
