<?php
	$firstName = "Urmot";
	$lastName = "Rosenberg";
	$dateToday = date("d.m.Y");
	$hourNow = date("G");
	$partOfDay = "";
	$dayOfWeek = date("N");
	$dayList = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	if ($hourNow < 8) {
		$partOfDay = "öö";
	} if ($hourNow >= 8 & $hourNow < 16) {
		$partOfDay = "koolipäev";
	} else {
		$partOfDay = "õhtu";
	}
	$pickNum = mt_rand(1, 43);
	//echo $pickNum;
	$pickURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
	$pickEXT = ".jpg";
	$pickFile = $pickURL.$pickNum.$pickEXT;
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
	<p>Tundides tehtud: <a href="foto.php">photo.php</a> ja <a href="page.php">page.php</a></p>
	<?php
	//echo "<p>Tänane kuupäev on: ".$dateToday.". Praegu on ".$partOfDay." (".date("H:i:s").").</p>\n";
	echo "<p>Täna on ".$dayList[$dayOfWeek-1].", ".$dateToday.". Praegu on ".$partOfDay." (".date("H:i:s").").</p>\n";
	?>
	<p>See on kodus lisatud rida</p>
	<!--img src="../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_2.jpg" alt="TLÜ Terra õppehoone">-->
	<img src = "<?php echo $pickFile ?>" alt="Suvaline pilt">
	<p>Mul on ka sõber, kes teeb oma <a href=../../../~rolavag/>veebi</a></p>
	<button onclick="location.href='http://www.tlu.ee'" type="button">
     Vajuta siia</button>
</body>
</html>
