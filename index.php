<?php
include 'content.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Gábor Görzsöny</title>
		<meta charset="utf-8" />
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<base href="http://localhost/gorzsony.com/" target="_self" />
		<link rel="stylesheet" href="style/style.css" />
		<script src="script/jquery-1.11.2.min.js"></script>
		<script src="script/site.js"></script>
	</head>
	<body>
		<header>
			<div>
				<img src="image/avatar.png" />
				<span>Gábor Görzsöny</span>
			</div>
		</header>
		<hr />
		<nav>
			<?php $content->displayNavLinks(); ?>
		</nav>
		<hr />
		<main>
			<?php $content->displayContent(); ?>
		</main>
		<hr />
		<footer>
			<p>&copy; 2015 Gábor Görzsöny<br />All rights reserved.</p>
		</footer>
	</body>
</html>