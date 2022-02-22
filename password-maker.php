<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>


password hash:
<br>
<?php echo password_hash('password', PASSWORD_DEFAULT) ?>
<br>
random token example:
<br>
<?php echo bin2hex(random_bytes(30)); ?>


</body>
</html>