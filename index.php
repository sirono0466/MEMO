<?php
	session_start();
	$_SESSION['username']="GUEST";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<form action="login.php" method="POST">
		username: <input type="text" name="username"><br>
		password: <input type="password" name="password"><br>
		<input type="submit" value="提交 (or just stay empty and press enter to login as a GUEST">
	</form>
</body>
</html>