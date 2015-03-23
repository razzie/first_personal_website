<?php
include 'content.php';

function isLocalhost()
{
    $localhost = array( '127.0.0.1', '::1' );
    if( in_array( $_SERVER['REMOTE_ADDR'], $localhost) )
        return true;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Gábor Görzsöny</title>
		<meta charset="utf-8" />
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php
		$base = isLocalhost() ? "localhost/" : "";
		echo "<base href=\"http://{$base}gorzsony.com/\ target=\"_self\" />";
		?>
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