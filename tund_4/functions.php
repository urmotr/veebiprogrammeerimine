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
	
	//anonüümse sõnumi salvestamine
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
	//anonüümse sõnumi lugemine
	
	function listallmessages(){
		$msgHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg");
		echo $mysqli->error;
		$stmt->bind_result($msg);
		$stmt->execute();
		while ($stmt->fetch()) {
			$msgHTML .= "<p> ".$msg . "</p> \n";
		}
		$stmt->close();
		return $msgHTML;
	}
	
?>