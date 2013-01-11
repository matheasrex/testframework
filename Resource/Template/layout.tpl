<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Framework test</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Framework test example">
		<meta name="author" content="matheasrex">

		<!-- Le styles -->
		<link href="<?=$config->get('url.static')?>css/bootstrap.css" rel="stylesheet">
		<link href="<?=$config->get('url.static')?>css/style.css" rel="stylesheet">
		<link href="<?=$config->get('url.static')?>css/bootstrap-responsive.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Fav icon -->
		<link rel="shortcut icon" href="<?=$config->get('url.static')?>img/favicon.ico">
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/">FwTest</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="/">Home</a></li>
							<li><a href="/main/first/">First page</a></li>
							<li><a href="/main/second/">Second page</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Hi, <? if (isset($userData) && $userData): ?><?=$userData?><? else: ?>Guest!<? endif ?>
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<? if (isset($userData) && $userData): ?>
										<li><a href="/logout/">Log out</a></li>
									<? else: ?>
										<li><a href="/login/">Log In</a></li>
									<? endif ?>
								</ul>
							</li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">
			<? $tpl->display($activeTemplate); ?>
			<hr>
			<footer>
				<p>@matheasrex 2013</p>
			</footer>
		</div> <!-- /container -->
		<script src="<?=$config->get('url.static')?>js/jquery.js"></script>
		<script src="<?=$config->get('url.static')?>js/bootstrap.js"></script>
	</body>
</html>
