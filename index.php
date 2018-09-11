<?php
	//echo "See on minu esimene PHP!";
	$firstName = "Urmot";
	$lastName = "Rosenberg";
	$dateToday = date("d.m.Y");
	$hourNow = date("G");
	$partOfDay = "";
	if ($hourNow <= 8) {
		$partOfDay = "öö";
	} if ($hourNow > 8 & $hourNow < 16) {
		$partOfDay = "päev";
	} else {
		$partOfDay = "õhtu";
	}
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
	echo "<p>Tänane kuupäev on: ".$dateToday.". Praegu on ".$partOfDay." (".date("H:i:s").").</p>\n";
	?>
	<p>See on kodus lisatud rida</p>
	<!--img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_2.jpg" alt="TLÜ Terra õppehoone">-->
	<img src="../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_2.jpg" alt="TLÜ Terra õppehoone">
	<p>Mul on ka sõber, kes teeb oma <a href=../../~rolavag/>veebi</a></p>
	<button onclick="location.href='http://www.tlu.ee'" type="button">
     Vajuta siia</button>
</body>
</html>
