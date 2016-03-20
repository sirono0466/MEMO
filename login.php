<?php
	session_start();
  	include_once("connectdb.php");
  	/*require('src/autoload.php');*/
?>
<?php
	/*$secret = '6LeDqAkTAAAAADm4ysHCNZfsZE8-NyKPh150Y8Pv';
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);
	$response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=",$secret."&response=".$_POST['g-recaptcha-response']);
	$response = json_decode($response);
	if($response->isSuccess()){
	}	
	else{
		echo "You are a robot";
	}*/

		if(!empty($_POST['username']) or !empty($_POST['password'])){
			$user = trim($_POST['username']);
			$pass = trim($_POST['password']);
			$salt = "127.0.0.1";
			//$pass = hash("sha256",$pass.$salt);
			@$sql = "SELECT * FROM user WHERE username ='".$user."'AND password = '".$pass."'LIMIT 1";
			$res = mysqli_query($con, $sql) or die(mysqli_error());
			if(mysqli_num_rows($res) == 1){
				$rows = mysqli_fetch_assoc($res);
				$_SESSION['username'] = $rows['username'];
				header("location:public.php");
			}
			else{
				echo "Invalid login information.";
				exit();
			}
		}
		else{
			$_SESSION['username']= "GUEST";
			header("location:public.php");
		}
?>
<?php

?>