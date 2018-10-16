<?php
	$firstName = "Kodanik";
	$lastName = "Tundmatu";
	
	//kontrollime, kas kasutaja on midagi lisanud
	//var_dump($_POST);
	if(isset($_POST["firstName"])){
		$firstName = $_POST["firstName"];
	}
	if(isset($_POST["lastName"])){
		$lastName = $_POST["lastName"];
	}
	$monthNow = date("m");
	$monthList =["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
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
	<hr>
	
	<form method="POST">
	<label>Eesnimi</lable>
	<input type="text" name="firstName">
	<label>Perekonnanimi</lable>
	<input type="text" name="lastName"><br>
	<label>Sünniaasta</lable>
	<input type="number" min="1900" max="2000" value="1999" name="birthYear">
	<label>Sünnikuu</lable>
	<select><?php
		for($i = 0; $i <= 11; $i++) {
			if($i == $monthNow-1) {
				echo '<option selected="selected" label="'.$monthList[$i].'">'.$monthList[$i].'</option>';
				} else {
				echo '<option label="'.$monthList[$i].'">'.$monthList[$i].'</option>';
			}
			};
			?>
	</select>
	<input type="submit" name="submitUserData" value="Saada andmed">
	</form>
	<hr>
	
	<?php
		if(isset($_POST["firstName"])){
		echo "<p>Olete elanud järgnevatel aastatel</p> \n";
		echo "<ol> \n";
			for($i = $_POST["birthYear"]; $i <= date("Y"); $i++) {
				echo "<li>".$i."</li>";
			}
		echo "</ol> \n";
	}
	?>
</body>
</html>