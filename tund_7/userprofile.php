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
	$description = mydescription();
	$mydescription = $description[0];
	$mybgcolor = $description[1];
	$mytextcolor = $description[2];
	if(isset($_POST["submitProfile"])){
		$notice = savemydescription($_POST["description"],$_POST["bgcolor"],$_POST["bgtext"]);
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
</head>
<body>
  <h1>Sõnumid</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
	</ul>
	<hr>
	<h2>Kasutaja profiili muutmine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
	<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label><input name="bgtext" type="color" value="<?php echo $mytextcolor; ?>"><br>
	<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>

</body>
</html>