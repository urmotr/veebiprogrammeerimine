<?php
	require("functions.php");
	require("design.php");
  
	$notice = "";
	$target_dir = "../vp_profile_pics/";
	$uploadOk = 1;
	
  
  if(!isset($_SESSION["userid"])){
		header("Location: index_1.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index_1.php");
		exit();
	}
	myprofilepic();
	$profilepic = $_SESSION["picid"];
	if(empty($profilepic)){
		$picaddress =  "../vp_picfiles/vp_user_generic.png";
	} else {
		$picaddress =  "../vp_profile_pics/".$profilepic;
		echo $picaddress;
	}
	$description = mydescription();
	$mydescription = $description[0];
	$mybgcolor = $description[1];
	$mytextcolor = $description[2];
	if(isset($_POST["submitProfile"])){
		$notice = savemydescription($_POST["description"],$_POST["bgcolor"],$_POST["bgtext"]);
	}
	$pagetitle = "Profiili muutmine";
	require("header.php");
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["tmp_name"])){
			$notice = "1";
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
			if($imageFileType != "jpg"  && $imageFileType != "jpeg") {
				$notice = "Vabandage, ainult JPG ja JPEG failid on lubatud.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$notice = "Vabandage, valitud faili ei saa üles laadida.";
			// if everything is ok, try to upload file
			} else {
				if($imageFileType = "jpg" or $imageFileType = "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 300;
				} else {
					$sizeRatio = $imageHeight / 300;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//Faili salvestamine
				if($imageFileType = "jpg" or $imageFileType = "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
						savemypic($target_file_name);
					} else {
						$notice = "Vabandage, tekkis tehniline viga.";
					}
				imagedestroy($myTempImage);
				imagedestroy($myImage);
			}
		}
	}
	}
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage,$image,0,0,0,0,$w,$h,$ow,$oh);
		return $newImage;
	}
?>
<!DOCTYPE html>
<body>
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
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
	<img src="<?php echo $picaddress; ?>" alt="Proov" height="300" width="300"><br>
	<input type="file" name="fileToUpload" id="fileToUpload"><br>
	<input type="submit" value="Lae pilt üles" name="submitImage"><br>
	<?php echo $notice;?>
	</form>
</body>
</html>