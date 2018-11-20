<?php
	class Photo_upload
	{
		private $tempName;
		private $imageFileType;
		private $myTempImage;
		private $myImage;
		
		
		function __construct($name,$type){
			$this->tempName = $name;
			$this->imageFileType = $type;
			$this->createImageFromFile();
		}
		
		function __destruct(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		private function createImageFromFile(){
			if($this->imageFileType = "jpg" or $this->imageFileType = "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			else if($this->imageFileType = "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			else if($this->imageFileType = "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		public function changePhotoSize($width,$height){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}	
			$newWidth = round($imageWidth / $sizeRatio);
			$newHeight = round($imageHeight / $sizeRatio);	
			$this->myImage = $this->resizeImage($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
		}
		private function resizeImage($image, $ow, $oh, $w, $h){
			$newImage = imagecreatetruecolor($w, $h);
			imagecopyresampled($newImage,$image,0,0,0,0,$w,$h,$ow,$oh);
			return $newImage;
		}	
		public function addWatermark(){
			//Vesimärgi lisamine
			$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
			$waterMarkWidth = imagesx($waterMark);
			$waterMarkHeight = imagesy($waterMark);
			$waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
			$waterMarkPosY = imagesy($this->myImage) - $waterMarkHeight - 10;
			imageCopy($this->myImage,$waterMark,$waterMarkPosX,$waterMarkPosY,0,0,$waterMarkWidth,$waterMarkHeight);
		}
		public function addTextWatermark($text){
			//Tekstvesimärgi lisamine
			$textToImage = $text;
			$textColor = imagecolorallocatealpha($this->myImage,255,255,111,60);
			imagettftext($this->myImage,16,0,10,40,$textColor,"../vp_picfiles/ARIALBD.TTF",$textToImage);
		}
		public function savePhoto($target_file){
			//Faili salvestamine
			$notice = null;
			if($this->imageFileType = "jpg" or $this->imageFileType = "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			} else if($this->imageFileType = "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			else if($this->imageFileType = "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
		return $notice;
		}
	}

?>