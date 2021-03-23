<html>
<?php require_once "app/views/header.php" ?>
<body>
<?php require_once "app/views/navbar.php" ?>
<?php if ($photo) { ?>
    <div class="container">
        <div class="jumbotron">
    <script src="/public/js/getlikes.js"></script>
    <input type="hidden" id="photo_id" value="<?php echo $photo['id'];?>"/>
    <img id="one_photo" src="<?php echo htmlspecialchars($photo['photo']);?>" alt="Фото">
    <br>
    <div class="likes">
        <button id="likeButton" class="btn btn-light">
            <svg id="like" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
            </svg>
            <span id="likesCount"></span>
        </button>
        <b><a href="\user\<?php echo $photo['login'];?>"><?php echo htmlspecialchars($photo['login']);?></a></b>
    </div>
    <p> <?php echo htmlspecialchars($photo['description'])?> </p>
    <p> <?php echo htmlspecialchars($photo['created_at']);?> </p>
    <?php if ($_SESSION["user_id"] === $photo['user_id']) { ?>
        <form name="delete" method="post" action="">
            <input type="hidden" name="photo_id" value="<?php echo $photo['id'];?>"/>
            <input type="hidden" name="user_id" value="<?php echo $photo['user_id'];?>"/>
            <input class="btn btn-outline-secondary" type="submit" name="delete" value="Delete"/>
        </form>
    <?php } ?>
    <br>
        </div>
    <h3>Комментарии</h3>
    <script src="/public/js/getcomments.js"></script>
    <div class="here" id="here2">
    </div>
    <br>
    <?php if (isset($_SESSION["user"]) && isset($_SESSION["user_id"]) &&
		$_SESSION["user"] !== "" && $_SESSION["user_id"] !== "") { ?>
        <form name="newcomment" method="post">
            <input type="hidden" id="photo_id" value="<?php echo $photo['id'];?>"/>
            <input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
            <textarea id="comment" cols="150" rows="5" placeholder="Комментировать..." ></textarea><br>
            <button id="commentButton" class="btn btn-outline-secondary">Отправить</button>
        </form>
    <?php } } ?>
    </div>
<script src="/public/js/onephoto_onload.js"></script>
<script src="/public/js/delete_comment.js"></script>
<?php require_once "app/views/footer.php" ?>
</body>
</html>