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
				if($imageFileType = "jpg" or $imageFileType = "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				else if($imageFileType = "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				else if($imageFileType = "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 600;
				} else {
					$sizeRatio = $imageHeight / 400;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//Vesimärgi lisamine
				$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$waterMarkWidth = imagesx($waterMark);
				$waterMarkHeight = imagesy($waterMark);
				$waterMarkPosX = $newWidth - $waterMarkWidth - 10;
				$waterMarkPosY = $newHeight - $waterMarkHeight - 10;
				imageCopy($myImage,$waterMark,$waterMarkPosX,$waterMarkPosY,0,0,$waterMarkWidth,$waterMarkHeight);
				
				//Tekstvesimärgi lisamine
				$textToImage = "Veebiprogrammeerimine";
				$textColor = imagecolorallocatealpha($myImage,255,155,0,60);
				imagettftext($myImage,16,0,10,$waterMarkPosY+$waterMarkHeight,$textColor,"../vp_picfiles/ARIALBD.TTF",$textToImage);
				
				
				//Faili salvestamine
				if($imageFileType = "jpg" or $imageFileType = "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
						addPhotoData($target_file_name,$_POST["altText"],$_POST["privacy"]);
					} else {
						$notice = "Vabandage, tekkis tehniline viga.";
					}
				}
				else if($imageFileType = "png"){
					if(imagepng($myImage, $target_file, 6)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
						addPhotoData($target_file_name,$_POST["altText"],$_POST["privacy"]);
					} else {
						$notice = "Vabandage, tekkis tehniline viga.";
					}
				}
				else if($imageFileType = "gif"){
					if(imagegif($myImage, $target_file)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
						addPhotoData($target_file_name,$_POST["altText"],$_POST["privacy"]);
					} else {
						$notice = "Vabandage, tekkis tehniline viga.";
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($waterMark);
				/*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
				} else {
					echo "Vabandage, tekkis tehniline viga.";
				}*/
			}
		}
	}
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage,$image,0,0,0,0,$w,$h,$ow,$oh);
		return $newImage;
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