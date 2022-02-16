<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title>GramOG</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css" integrity="sha512-xiunq9hpKsIcz42zt0o2vCo34xV0j6Ny8hgEylN3XBglZDtTZ2nwnqF/Z/TTCc18sGdvCjbFInNd++6q3J0N6g==" crossorigin="anonymous" />

	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="<?php echo $page; ?>">
<div class="site">
	<header class="header">
		<h1><a href="index.php">GramOG</a></h1>

		<nav class="main-navigation">
			<form class="searchform" method="get" action="search.php">
				<label class="screen-reader-text" for="phrase">Search:</label>
				<input type="search" name="phrase" id="phrase">

				<input type="submit" value="Search">
			</form>
		</nav>
	</header>