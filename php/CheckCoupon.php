<?php 
require "ProcessData.php";
require "constant.php";

if(!empty($_POST["couponcode"])){$couponcode = $_POST["couponcode"];}
	$msg = "";
	$servername = DATASERVER;
	$dbname = DATABASE;
	$Date = date("Y-m-d H:i:s");
	$sql = "SELECT `Code`, `Discount` FROM `coupon` WHERE `Code`='$couponcode' AND `FromDate`<='$Date' AND `ToDate`>='$Date' AND `Status`=" . ACTIVE;
	

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchall();
		if(!empty($result[0]['Code'])){
			$msg = "1-Mã Coupon đã được xác thực-" . $result[0]['Discount'];
		}
		else{
			$msg = "0-Mã Coupon không đúng";
		}
	}
	catch(PDOException $e){
			$msg =  "0-Error:" . $e->getMessage();
	}
	$conn = null;
	echo $msg;
?>