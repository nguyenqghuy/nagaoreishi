<?php 
require "ProcessData.php";
require "constant.php";

if(!empty($_GET["email"])){
	
	$Date = date("Y-m-d H:i:s");
	$OrderId = date("Ymd") . GetLastId();
	$FirstName = "Guest";
	$Email = test_input($_GET["email"]);
	Insert_Input();
}

function Insert_Input(){
	$servername = DATASERVER;
	$username = USER;
	$password = PASSWORD;
	$dbname = DATABASE;
	$sql = "INSERT INTO `order` (`OrderId`, `OrderDate`, `FirstName`, `Email`,  `Comment`, `Status`) VALUES(  '";
	$sql .= $GLOBALS['OrderId'] . "', '" . $GLOBALS['Date'] . "', '" . $GLOBALS['FirstName'] . "', '";
	$sql .= $GLOBALS['Email'] . "', ''," . NOT_ORDER . ")" ;
		

/*	echo $sql;
*/
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($sql);
		$stmt->execute();

	}
	catch(PDOException $e){
			echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>