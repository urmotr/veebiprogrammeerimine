<?php
	require("functions.php");
	
	$cats = listallcats();
	
	$notice = null;
	
	if(!empty($_POST["catname"]) and !empty($_POST["catcolor"])  and !empty($_POST["cattaillength"])) {
			$catname = test_input($_POST["catname"]);
			$catcolor = test_input($_POST["catcolor"]);
			$cattaillength = test_input($_POST["cattaillength"]);
			$notice = savecat($catname,$catcolor,$cattaillength);
		} else {
			$notice = null;
		}		
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kiisu andmebaasi lisamine</title>
</head>
<body>
	<h1>Kiisu lisamine</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank"> TLÜ</a> õppetöö raames, ei pruugi parim välja näha ja kindlasti ei sialda tõsiselt võetavat sisu!</p>
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Kiisu nimi</lable>
	<input type="text" name="catname">
	<br>
	<label>Kiisu värvus</lable>
	<input type="text" name="catcolor">
	<br>
	<label>Kiisu saba pikkus</lable>
	<input type="number" name="cattaillength">
	<input type="submit" name="submitCat" value="Salvesta kiisu andmed">
	</form>
	<hr>
	<ol>
	<?php
		echo $cats;
	?>
	</ol>
</body>
</html>