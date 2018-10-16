<?php
	require("functions.php");
	require("design.php");
	
	if(!isset($_SESSION["userid"])){
		header("Location: index_1.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index_1.php");
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>pealeht</title>
	</head>
	<body>
		<h1>Pealeht</h1>
		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
		<hr>
		<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstname"]. " ".$_SESSION["lastname"]."."; ?></p>
		<ul>
			<?php echo userslist(); ?>
		</ul>
	
	</body>
</html>