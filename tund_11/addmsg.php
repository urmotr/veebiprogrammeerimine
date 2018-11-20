<?php
	require("functions.php");
	require("design.php");
	
	$notice = null;
	
	if (isset($_POST["submitMessage"])) {
		if ($_POST["message"] != "Siia sisesta enda sõnum..." and !empty($_POST["message"])) {
			$message = test_input($_POST["message"]);
			$notice = saveamsg($message);
		} else {
			$notice = "Palun sisestage sõnum";
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümse sõnumi lisamine</title>
</head>
<body>
	<h1>Sõnumi lisamine</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank"> TLÜ</a> õppetöö raames, ei pruugi parim välja näha ja kindlasti ei sialda tõsiselt võetavat sisu!</p>
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Sõnum (max 256 märki)</lable>
	<br>
	<textarea rows="4" cols="64" name="message">Siia sisesta enda sõnum...</textarea>
	<input type="submit" name="submitMessage" value="Salvesta sõnum">
	</form>
	<hr>
	<p><?php echo $notice ?></p>
</body>
</html>