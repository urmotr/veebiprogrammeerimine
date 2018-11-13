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
	
	require("classes/Photo_upload.class.php");
	/*require("classes/Test.class.php");
	$myTest = new Test(4);
	echo $myTest->publicNumber;
	echo $myTest->tellInfo();
	unset($myTest);*/
	
	$notice = "";
	$target_dir = "../vp_pic_uploads/";
	$uploadOk = 1;
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["tmp_name"])){
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file_name = "vp_".$timeStamp.".".$imageFileType;
			$target_file = $target_dir . $target_file_name;
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice = "";
			} else {
				$notice = "See ei ole pilt.";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 50000000) {
				$notice = "Vabandage, pilt on liiga suur.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$notice = "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$notice = "Vabandage, valitud faili ei saa üles laadida.";
			// if everything is ok, try to upload file
			} else {
				$myPhoto = new Photo_upload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->changePhotoSize(600,400);
				$myPhoto->addWatermark();
				$myPhoto->addTextWatermark();
				$noticed = $myPhoto->savePhoto($target_file);
				unset($myPhoto);
				if($noticed = 1){
					addPhotoData($target_file_name,$_POST["altText"],$_POST["privacy"]);
				} else {
					$notice = "Vabandame, tekkis viga";
				}
				
				}
			}
		}
	
	$pagetitle = "Fotode üleslaadimine";
	require("header.php");
?>

		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
		<hr>
		<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstname"]. " ".$_SESSION["lastname"]."."; ?></p>
		<ul>
			<li><a href="main.php">Pealehele</a></li>
			<li><a href="?logout=1">Logi välja!</a></li>
		</ul>
		<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
		<label>Vali üleslaetava pildi fail:</label><br>
		<input type="file" name="fileToUpload" id="fileToUpload"><br>
		<label>Alt tekst: </label>
		<input type="text" name="altText"><br>
		<label>Määra pildi kasutusõigused</label><br>
		<input type="radio" name="privacy" value="1"><label> Avalik pilt</label>
		<input type="radio" name="privacy" value="2"><label> Ainult sisseloginud kasutajatele</label>
		<input type="radio" name="privacy" value="3" checked><label> Privaatne</label><br>
		<input type="submit" value="Lae pilt üles" name="submitImage"><br>
		<?php echo $notice;?>
	</form>
	</body>
</html>