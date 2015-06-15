<?php
function isLocalhost()
{
	$localhost = array( '127.0.0.1', '::1' );
	if( in_array($_SERVER['REMOTE_ADDR'], $localhost) )
		return true;
	else
		return false;
}

if (isLocalhost())
{
	$base = "localhost:{$_SERVER['SERVER_PORT']}/";
}
else
{
	$base = 'www.';
	
	// redirect from gorzsony.com to www.gorzsony.com
	$protocol = (@$_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
	if (strncmp($_SERVER['HTTP_HOST'], 'www.', 4) !== 0)
	{
		header("Location: {$protocol}www.{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
		exit;
	}
}

chdir('core');
include 'content.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $content->getTitle(); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<base href="http://<?php echo $base; ?>gorzsony.com/" target="_self" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="core/style.css" />
		<link rel="stylesheet" href="core/featherlight.min.css" />
		<script src="core/jquery-1.11.2.min.js"></script>
		<script src="core/jquery.history.js"></script>
		<script src="core/featherlight.min.js"></script>
		<script src="core/main.js"></script>
	</head>
	<body>
		<header>
			<div class="title">
				Gábor Görzsöny
				<span>C++ programmer and IT enthusiast</span>
			</div>
			<div class="social">
				<a href="mailto:gabor@gorzsony.com">
					<img src="image/email.png" alt="Email" />
				</a>
				<a href="http://twitter.com/gorzsony" target="_blank">
					<img src="image/twitter.png" alt="Twitter" />
				</a>
				<a href="http://facebook.com/gabor.gorzsony" target="_blank">
					<img src="image/facebook.png" alt="Facebook" />
				</a>
				<a href="http://linkedin.com/in/gorzsony" target="_blank">
					<img src="image/linkedin.png" alt="LinkedIn" />
				</a>
				<a href="http://soundcloud.com/gorzsony/" target="_blank">
					<img src="image/soundcloud.png" alt="Soundcloud" />
				</a>
			</div>
		</header>
		<nav>
			<ul>
				<?php $content->displayNavLinks(); ?>
			</ul>
		</nav>
		<main>
			<?php $content->displayContent(); ?>
		</main>
		<footer>
			&copy; 2015 Gábor Görzsöny - All rights reserved.
		</footer>
	</body>
</html>