<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $config['site_title']; ?></title>
		<meta name="description" content="<?php echo $config['site_description']; ?>" />
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=10, minimum-scale=1.0">
		<meta name="keywords" content="PODOLOGIA" />
		<link rel="icon" href="/layout/icon.ico">
		
		<!-- Stylesheet(s) -->
		<link rel="stylesheet" type="text/css" href="/layout/css/style.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/animations.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/inputs.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/tables.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/tipped.css"/>
		<link rel="stylesheet" type="text/css" href="/layout/css/dashboard.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<!-- JavaScript(s) -->
		<script type="text/javascript" src="/layout/js/jquery.min.js"></script>
		<script type="text/javascript" src="/layout/js/jquery.sooperfish.js"></script>
		<script type="text/javascript" src="/layout/js/tipped.js"></script>
		<script type="text/javascript" src="/layout/js/jquery.mask.js"></script>
		<script type="text/json" src="/layout/js/composer.json"></script>
		
		<!-- SVG(s) -->
		
		<!-- Json(s) -->

		<!-- Script(s) -->
		<script>
			$(document).ready(function() {
				Tipped.create('.tooltip');
			});
		</script>
	</head>
	<body>
		<!-- Main container -->
		<header>
		</header>
		<main>
			<aside id="body">
				<!-- MAIN FEED -->
				<?php include 'complement/menu-left.php'; ?>
					<div id="border-content">
					<!-- <div id="title-border"><img src="layoutDesign/titlebar.png"/><p id="title-page"><?php echo $titlepage ?></p></div>-->
						<div id="content">
