<?php
	$firstName = "Urmot";
	$lastName = "Rosenberg";
	//loeme piltide kataloogi sisu
	$dirToRead = "../../pics/";
	$allFiles = scandir($dirToRead);
	$picFiles = array_slice($allFiles, 2);
	//var_dump($picFiles);
	$randomPic = mt_rand(0, max(array_keys($picFiles)));
	$picFullAddress = $dirToRead.$picFiles[$randomPic];
	//echo $picFullAddress
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $firstName." ".$lastName; ?>, õppetöö</title>
</head>
<body>
	<h1><?php echo $firstName," ",$lastName; ?></h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank"> TLÜ</a> õppetöö raames, ei pruugi parim välja näha ja kindlasti ei sialda tõsiselt võetavat sisu!</p>
	<?php
	//<img src = echo $picFullAddress; " alt="Suvaline pilt" height="620" width="940">
	echo '<img src = "' .$picFullAddress.'"alt="Suvaline pilt" height="620" width="940">'
	//for ($i = 0; $i <= max(array_keys($picFiles))){
	/*for ($i = 0; $i < count($picFiles); $i++) {
		echo '<img src = "' .$dirToRead.$picFiles[$i].'"alt="Suvaline pilt" height="620" width="940"'."\n".'>';
	}*/
	?>
</body>
</html>
