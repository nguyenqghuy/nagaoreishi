<?php 
require "../../php/ProcessData.php";
require "../../php/constant.php";

if(!empty($_POST["Phone"])){$Phone = $_POST["Phone"];}
if(!empty($_POST["Id"])){$Id = $_POST["Id"];}
	$msg = "";
	$servername = DATASERVER;
	$dbname = DATABASE;
	$Date = date("Y-m-d H:i:s");
	$sql = "UPDATE `order` SET `Telphone`=$Phone WHERE `ID`=$Id";
	

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$msg = "1";
	}
	catch(PDOException $e){
			$msg =  "0-Error:" . $e->getMessage();
	}
	$conn = null;
	echo $msg;
?>