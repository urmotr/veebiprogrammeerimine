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
	$target_dir = $picDir;
	$thumb_dir = $thumbDir;
	$thumbSize = 100;
	$target_file = "";
	$uploadOk = 1;
	//$imageFileType = "";
	$imageNamePrefix = "vp_";
    $textToImage = "Veebiprogrammeerimine";
    $pathToWatermark = "../vp_picfiles/vp_logo_w100_overlay.png";
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["tmp_name"])){
			$myPhoto = new Photoupload($_FILES["fileToUpload"]);
			$myPhoto->readExif();
			$myPhoto->makeFileName($imageNamePrefix);
			//määrame faili nime
			$target_file = $target_dir .$myPhoto->fileName;
			
			//kas on pilt
			$uploadOk = $myPhoto->checkForImage();
			if($uploadOk == 1){
			  // kas on sobiv tüüp
			  $uploadOk = $myPhoto->checkForFileType();
			}
			
			if($uploadOk == 1){
			  // kas on sobiv suurus
			  $uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
			}
			
			if($uploadOk == 1){
			  // kas on juba olemas
			  $uploadOk = $myPhoto->checkIfExists($target_file);
			}
						
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$notice = "Vabandame, faili ei laetud üles! Tekkisid vead: ".$myPhoto->errorsForUpload;
			// kui kõik korras, laeme üles
			} else {
				$myPhoto->createThumbnail($thumb_dir,$thumbSize);
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark($pathToWatermark);
				$myPhoto->addText();
				$saveResult = $myPhoto->savePhoto($target_file);
				//kui salvestus õnnestus, lisame andmebaasi
				if($saveResult == 1){
				  $notice = "Foto laeti üles! ";
				  $notice .= addPhotoData($myPhoto->fileName, $_POST["altText"], $_POST["privacy"]);
				} else {
                  $notice .= "Foto lisamisel andmebaasi tekkis viga!";
                }
				
			}
			unset($myPhoto);
		}//ega failinimi tühi pole
	}//kas on submit nuppu vajutatud
  
  //lehe päise laadimise osa
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