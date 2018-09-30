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
			$notice = "Sõnumi salvestamisel tekkis tõrge: ".$stmt->error;
		}
		$stmt->close();
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