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
	
	$pagetitle = "Pealeht";
	require("header.php");
?>

		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
		<hr>
		<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstname"]. " ".$_SESSION["lastname"]."."; ?></p>
		<ul>
			<li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid!</a></li>
			<li><a href="validatedmessages.php">Näita valideerituid sõnumeid valideerijate kaupa!</a></li>
			<li><a href="users.php">Kasutajate loetelu!</a></li>
			<li><a href="userprofile.php">Profiili muutmine!</a></li>
			<li><a href="photo_upload_uus.php">Fotode üleslaadimine!</a></li>
			<li><a href="pubgallery.php">Avalike piltide galerii!</a></li>
			<li><a href="privatepics.php">Minu privaatsed pildid!</a></li>
			<li><a href="?logout=1">Logi välja!</a></li>
		</ul>
	
	</body>
</html>