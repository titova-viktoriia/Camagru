<html>
<?php require_once "app/views/header.php" ?>
<body>
<?php require_once "app/views/navbar.php" ?>
    <?php if ($user['activated'] === "1") { ?>
        <div class="container">
            <div class="jumbotron">
				<?php if ($user['photo'] === NULL) { ?>
                    <img id="users-photo" src="/public/img/user.png" alt="Фото пользователя">
				<?php } else { ?>
                    <img src="<?php echo htmlspecialchars($user['photo']);?>" alt="Фото пользователя" width=150px>
				<?php } ?>
                <div id="users-info">
                    <br><p> <?php echo htmlspecialchars($user['name']);?> <?php echo htmlspecialchars($user['surname']);?></p>
                    <p> Email: <?php echo htmlspecialchars($user['email']);?>.</p>
                </div>
            </div>
        </div>
			<?php if (isset($user['photodata'])) { ?>
					<?php if (isset($user['photodata']['photos'])) { ?>
                <center>
                    <div class="row2">
                        <div class="col-lg-9">
                            <div id="carouselExampleIndicators" class="carousel slide my-4">
                                <div class="row">
									<?php foreach ($user['photodata']['photos'] as $value) { ?>
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="card w-200">
                                                <a href="\photo\<?php echo $value['id'];?>"><img class="card-img-top" src="<?php echo htmlspecialchars($value['photo']);?>" alt="" ></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </center>
						<?php }
					} ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
				<?php
				if ($user['photodata']['pageCount'] > 1 && $user['photodata']['pageNumber'] > 1) { ?>
					<li class="page-item"><a class="page-link" href="/user/<?php echo $user['login'];?>?page=1">The first</a></li>
				<?php }
				if ($user['photodata']['pageCount'] > 1 && $user['photodata']['pageNumber'] > 1) {
					$previous =  $user['photodata']['pageNumber'] - 1; ?>
					<li class="page-item"><a class="page-link" href="/user/<?php echo $user['login'];?>?page=<?php echo $previous;?>">Previous</a></li>
				<?php }
				if ($user['photodata']['pageCount'] > 1 && $user['photodata']['pageNumber'] < $user['photodata']['pageCount']) {
					$next =  $user['photodata']['pageNumber'] + 1; ?>
					<li class="page-item"><a class="page-link" href="/user/<?php echo $user['login'];?>?page=<?php echo $next;?>">Next</a></li>
				<?php }
				if ($user['photodata']['pageCount'] > 1 && $user['photodata']['pageNumber'] !== $user['photodata']['pageCount']) { ?>
					<li class="page-item"><a class="page-link" href="/user/<?php echo $user['login'];?>?page=<?php echo $user['photodata']['pageCount'];?>">The last</a></li>
				<?php } ?>
            </ul>
        </nav>
		<?php
    } else { ?>
        <p> Доступ запрещен, пользователь не активирован </p>
    <?php } ?>
</body>
<?php require_once "app/views/footer.php" ?>
</html>
