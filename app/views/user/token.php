<html>
<?php require_once "app/views/header.php"; ?>
<body>
<?php require_once "app/views/navbar.php" ?>
<div class="container">
<h1 class="display-4">Token</h1>
<?php
if ($message !== "") {
	echo "<p>" . htmlspecialchars($message) . "</p>";
	$message = "";
}
?>
<?php require_once "app/views/footer.php" ?>
</div>
</body>
</html>