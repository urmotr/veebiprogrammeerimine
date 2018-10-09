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
	
	function validatemsg($accepted, $userid, $timenow){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);	
		$stmt = $mysqli->prepare("UPDATE vpamsg SET accepted=?, acceptedby=?, accepttime=now() WHERE id=?");
		$stmt->bind_param("is", $_POST["validation"], $_SESSION["userid"]);
		$stmt->close();
		$mysqli->close();
		return $notice;
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