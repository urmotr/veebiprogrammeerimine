<?php
	//tekstisisestuse kontroll
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	
	$database = "if18_urmot_ro_1";
	session_start();
	
	function myprofilepic(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT picid FROM profiil WHERE userID=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($picid);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch()){
			$notice = $picid;
			$_SESSION["picid"] = $picid;
			$stmt2 = $mysqli->prepare("SELECT picname FROM profilepic WHERE id=?");
			echo $mysqli->error;
			$stmt2->bind_param("i", $picid);
			$stmt2->bind_result($picname);
			$stmt2->execute();
			$stmt2->store_result();
			if($stmt2->fetch()){
				$notice = $picname;
				$_SESSION["picid"] = $picname;
			}
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function savemypic($picname){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO profilepic (userid,picname) VALUES(?,?)");
		$stmt->bind_param("is", $_SESSION["userid"], $picname);
		$stmt2 = $mysqli->prepare("UPDATE profiil SET picid=? WHERE userID=?");
		echo $mysqli->error;
		if($stmt->execute()){
			$notice = "Salvestamine õnnestus";
			$picid = $mysqli->insert_id;
			$stmt2->bind_param("ii", $picid, $_SESSION["userid"]);
			if($stmt2->execute()){
				$notice = "Salvestamine õnnestus";
				header("Location: userprofile.php");
				exit();
			} else{
				echo "Appi";
			}
			return $notice;
			header("Location: userprofile.php");
			exit();
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
	}
	
	function addPhotoData($fileName,$altText,$privacy){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpphotos (userID, filename,alttext,privacy) VALUES(?,?,?,?)");
		echo $mysqli->error;
		if(empty($privacy)){
			$privacy = 3;
		}
		$stmt->bind_param("issi", $_SESSION["userid"],$fileName,$altText,$privacy);
		if($stmt->execute()){	
			$notice = "Salvestamine õnnestus";
		}else{
			$notice = "Kõik on pahasti";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function mydescription(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT description, bgcolor, textcolor FROM profiil WHERE userID=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($description,$bgcolor,$textcolor);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch()){
			$notice = array($description,$bgcolor,$textcolor);
			$_SESSION["bgcolor"] = $bgcolor;
			$_SESSION["textcolor"] = $textcolor;
		} else {
			$notice = array("Pole iseloomustust lisanud.","#FFFFFF","#000000");
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function savemydescription($description1,$bgcolor1,$textcolor1){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT description, bgcolor, textcolor FROM profiil WHERE userID=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($description,$bgcolor,$textcolor);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch()){
			$stmt2 = $mysqli->prepare("UPDATE profiil SET userID=?, description=?, bgcolor=?,textcolor=? WHERE userID=?");
			$stmt2->bind_param("isssi", $_SESSION["userid"], $description1, $bgcolor1, $textcolor1 ,$_SESSION["userid"]);
			if($stmt2->execute()){
				header("Location: userprofile.php");
				exit();
			}
		} else {
			$stmt2 = $mysqli->prepare("INSERT INTO profiil (userID, description,bgcolor,textcolor) VALUES(?,?,?,?)");
			echo $mysqli->error;
			$stmt2->bind_param("isss", $_SESSION["userid"],$description1,$bgcolor1,$textcolor1);
			$stmt2->execute();
			$notice = "Salvestamine õnnestus";
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function readallvalidatedmessagesbyuser(){
		$msghtmlfull = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($userIdFromDb,$firstNameFromDb,$lastNameFromDb);
		$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
		echo $mysqli->error;
		$stmt2->bind_param("i", $userIdFromDb);
		$stmt2->bind_result($messageFromDb,$acceptedFromDb);
		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch()){
			$count = 0;
			$msghtml = "";
			$msghtml .= "<h3>".$firstNameFromDb." ".$lastNameFromDb."</h3> \n";
			$stmt2->execute();
			while($stmt2->fetch()){
				$count = 1;
				$msghtml .= "<p><b>";
				if($acceptedFromDb == 1){
					$msghtml.= "Lubatud" ;
					} else {
					$msghtml.= "Keelatud" ;
					}
				$msghtml .= ": </b>".$messageFromDb."</p> \n";
			}
			if($count != 0){
				$msghtmlfull .= $msghtml;
			}
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
		return $msghtmlfull;
	}
	
	function allvalidmessages(){
		$notice = "";
		$accepted = 1;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted = ?");
		$stmt->bind_param("i", $accepted);
		$stmt->bind_result($msg);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<p>" .$msg ."</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
		
	function userslist(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id != ?");
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($firstname,$lastname,$email);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<li>" .$firstname." ".$lastname." : ".$email."</li> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function validatemsg($accepted, $id){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);	
		$stmt = $mysqli->prepare("UPDATE vpamsg SET accepted=?, acceptedby=?, accepttime=now() WHERE id=?");
		$stmt->bind_param("iii", $accepted, $_SESSION["userid"],$id);
		if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	}
	
	function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
	}
	
	function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$notice .= "<ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
	
	function saveamsg($msg){
		$notice = "";
		//serveri ühendis (server, kasutaja, parool, andmebaas)
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistan ette mysql käsi
		$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
		echo $mysqli->error;
		//asendame sql käsus "?" päris infoga ("andmetüüp", andmed ise)
		//s - string, i - int, d - decimal(murdarv)
		$stmt->bind_param("s", $msg);
		if($stmt->execute() == true){
			$notice = 'Sõnum: "'.$msg.'" on salvestatud';
		} else {
			$notice = "Sõnumi salvestamisel tekkis tõrge: ".$stmt->error;
		}
		$stmt->close();
		return $notice;
	}
	
	function signin($email,$password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
		$mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb , $passwordFromDb);
		if($stmt->execute()){
			//kui õnnestus andmebaasist lugemine
			if($stmt->fetch()){
				if(password_verify($password, $passwordFromDb)){
					$notice =  "Sisselogimine õnnestus";
					$_SESSION["userid"] = $idFromDb;
					$_SESSION["firstname"] = $firstnameFromDb;
					$_SESSION["lastname"] = $lastnameFromDb;
					$stmt->close();
					$mysqli->close();
					header("Location: main.php");
					exit();
				} else {
					$notice = "Sisestatud salasõna on vale";
				}
			} else {
				$notice = "Sellist kasutajat (".$email.")ei leitud!";
			}
		} else {
			$notice = "Sisselogimisel tekkis tehniline viga!".$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	function loadprofile(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);	
		$stmt = $mysqli->prepare("SELECT description, bgcolor, textcolor FROM profiil WHERE userID=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($description,$bgcolor,$textcolor);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch()){
			$_SESSION["bgcolor"] = $bgcolor;
			$_SESSION["textcolor"] = $textcolor;
		} else {
			$_SESSION["bgcolor"] = "#FFFFFF";
			$_SESSION["textcolor"] = "#000000";
		}
		$stmt->close();
		$mysqli->close();
	}
	function signup($firstName,$lastName,$birthdate,$gender,$email,$password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);	
		$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
			$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
			$stmt->close();
			$mysqli->close();
			return $notice;
		} else {
			$stmt->close();
			$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
		echo $mysqli->error;
		//valmistame parooli ette salvestamiseks - krüpteerime, teeme räsi (hash)
		$options = [
			"cost" => 12,
			"salt" => substr(sha1(rand()),0,22)
		];
		$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
		$stmt->bind_param("sssiss", $firstName,$lastName,$birthdate,$gender,$email,$pwdhash);
		if($stmt->execute() == true){
			$notice = 'Uue kasutaja lisamine õnnestus!';
		} else {
			$notice = "kasutaja lisamisel tekkis viga: ".$stmt->error;
		}
	}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//anonüümse sõnumi salvestamine
	
	function savecat($catname,$catcolor,$cattaillenght){
		$notice = "";
		//serveri ühendis (server, kasutaja, parool, andmebaas)
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistan ette mysql käsi
		$stmt = $mysqli->prepare("INSERT INTO kiisud (nimi,v2rv,saba) VALUES(?,?,?)");
		echo $mysqli->error;
		//asendame sql käsus "?" päris infoga ("andmetüüp", andmed ise)
		//s - string, i - int, d - decimal(murdarv)
		$stmt->bind_param("ssi", $catname,$catcolor,$cattaillenght);
		if($stmt->execute() == true){
			$notice = 'Kiisu: "'.$catname.'" on salvestatud';
		} else {
			$notice = "Kiisu salvestamisel tekkis tõrge: ".$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//anonüümse sõnumi lugemine
	
	function listallcats(){
		$msgHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT nimi,v2rv,saba FROM kiisud");
		echo $mysqli->error;
		$stmt->bind_result($name,$color,$taillenght);
		$stmt->execute();
		while ($stmt->fetch()) {
			$msgHTML .= "<li>".$name." ".$color. " " .$taillenght . "</li> \n";
		}
		$stmt->close();
		return $msgHTML;
	}
	
?>