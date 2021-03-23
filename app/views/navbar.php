<!-- Add navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light" id="navcont">
	<!-- Brand -->
	<a class="navbar-brand" href="/">
		<!--  <img src="public/img/brand.png" width="100" height="100" class="d-inline-block align-top" alt="">-->
		Camagru
	</a>
	<!-- Navbar toggler -->
	<button class="navbar-toggler" type="button" data-toggle="dropdown" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" id="menuButton" onclick="showMenu();">
		<span class="navbar-toggler-icon"></span>
	</button>
	<!-- Collapsable area -->
	<div class="collapse navbar-collapse" id="menu">
		<ul class="navbar-nav">
			<?php if (isset($_SESSION['user']) && $_SESSION['user'] !== "") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/photo">Камера</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user/<?php echo $_SESSION['user'];?>">Моя страница</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/settings">Настройки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Выйти</a>
                </li>
			<?php } else {?>
			    <li class="nav-item">
				    <a class="nav-link" href="/registration">Регистрация</a>
			    </li>
			    <li class="nav-item">
				    <a class="nav-link" href="/login">Войти</a>
			    </li>
                <li class="nav-item">
				    <a class="nav-link" href="/recovery">Восстановить пароль</a>
			    </li>
			<?php } ?>
		</ul>
	</div>
</nav>
<script src="/public/js/menu.js"></script>