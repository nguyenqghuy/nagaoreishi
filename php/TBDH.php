<?php 
	require "ProcessData.php";

	$FirstName = $LastName = $Phone = $Address = $City = $Email = $Connect = $XL = $NT = $CC = $CK = $TP = $Payment = $PriceXL = $PriceNT = $PriceCC = $PriceCK = $PriceTP = $Comment = $Status = "";
	$TotalHidden = $SubTotalHidden = $DiscountConnectHidden = $DiscountPaymentHidden = $DiscountValueHidden = $DiscountCouponHidden = 0;
	$CouponCodeHidden = "";
	$Date = date("Y-m-d H:i:s");
	$OrderId = date("Ymd") . GetLastId();
	
	if(!empty($_POST["firstname"])){ $FirstName = test_input($_POST["firstname"]);}
	if(!empty($_POST["lastname"])){ $LastName = test_input($_POST["lastname"]);}
	if(!empty($_POST["phone"])){ $Phone = test_input($_POST["phone"]);}
	if(!empty($_POST["address"])){ $Address = test_input($_POST["address"]);}
	if(!empty($_POST["city"])){ $City = test_input($_POST["city"]);}
	if(!empty($_POST["Email"])){ $Email = test_input($_POST["Email"]);}
	if(!empty($_POST["Connect"])){ $Connect = test_input($_POST["Connect"]);}
	if(!empty($_POST["XL"])){ $XL = test_input($_POST["XL"]);}
	if(!empty($_POST["NT"])){ $NT = test_input($_POST["NT"]);}
	if(!empty($_POST["CC"])){ $CC = test_input($_POST["CC"]);}
	if(!empty($_POST["CK"])){ $CK = test_input($_POST["CK"]);}
	if(!empty($_POST["TP"])){ $TP = test_input($_POST["TP"]);}
	if(!empty($_POST["Payment"])){ $Payment = test_input($_POST["Payment"]);}
	if(!empty($_POST["comment"])){ $Comment = test_input($_POST["comment"]);}
	if(!empty($_POST["TotalHidden"])){ $TotalHidden = test_input($_POST["TotalHidden"]);}
	if(!empty($_POST["SubTotalHidden"])){ $SubTotalHidden = test_input($_POST["SubTotalHidden"]);}
	if(!empty($_POST["DiscountConnectHidden"])){ $DiscountConnectHidden = test_input($_POST["DiscountConnectHidden"]);}
	if(!empty($_POST["DiscountPaymentHidden"])){ $DiscountPaymentHidden = test_input($_POST["DiscountPaymentHidden"]);}
	if(!empty($_POST["DiscountValueHidden"])){ $DiscountValueHidden = test_input($_POST["DiscountValueHidden"]);}
	if(!empty($_POST["PriceXL"])){ $PriceXL = test_input($_POST["PriceXL"]);}
	if(!empty($_POST["PriceNT"])){ $PriceNT = test_input($_POST["PriceNT"]);}
	if(!empty($_POST["PriceCC"])){ $PriceCC = test_input($_POST["PriceCC"]);}
	if(!empty($_POST["PriceCK"])){ $PriceCK = test_input($_POST["PriceCK"]);}
	if(!empty($_POST["PriceTP"])){ $PriceTP = test_input($_POST["PriceTP"]);}
	if(!empty($_POST["CouponCodeHidden"])){ $CouponCodeHidden = test_input($_POST["CouponCodeHidden"]);}
	if(!empty($_POST["DiscountCouponValueHidden"])){ $DiscountCouponValueHidden = test_input($_POST["DiscountCouponValueHidden"]);}
	
	//proccess Phone to format 849102382920
	$Phone = trim($Phone);
	$Phone = ltrim($Phone,"0"); 
	$Phone = str_replace(" ","",$Phone);
	$Phone = "84" . $Phone;
	
	if($Connect == 'connect'){
		$Connect = 1;
	}else{
		$Connect = 0;
	}
	$SubTotal = $SubTotalHidden;
	$SubTotalHidden = str_replace('.','',$SubTotalHidden);
	$Total = $TotalHidden;
	$TotalHidden = str_replace('.','',$TotalHidden);
	$DiscountConnect = $DiscountConnectHidden;
	$DiscountConnectHidden = str_replace('.','',$DiscountConnectHidden);
	if($DiscountConnectHidden == "-"){$DiscountConnectHidden = "0";}
	$DiscountPayment = $DiscountPaymentHidden;
	$DiscountPaymentHidden = str_replace('.','',$DiscountPaymentHidden);
	if($DiscountPaymentHidden == "-"){$DiscountPaymentHidden = "0";}
	$DiscountValue = $DiscountValueHidden;
	$DiscountValueHidden = str_replace('.','',$DiscountValueHidden);
	if($DiscountValueHidden == "-"){$DiscountValueHidden = "0";}
	$DiscountCouponValue = $DiscountCouponValueHidden;
	$DiscountCouponValueHidden = str_replace('.','',$DiscountCouponValueHidden);
	if($DiscountCouponValueHidden == "-"){$DiscountCouponValueHidden = "0";}

	
	$OrderDetail = "XL:" . $PriceXL . ":" . $XL . "-NT:" . $PriceNT . ":" . $NT . "-CC:" .  $PriceCC . ":" .  $CC . "-CK:" .  $PriceCK . ":" .  $CK . "-TP:" .  $PriceTP . ":" .  $TP;
	if(!empty($_POST["firstname"]) && !empty($_POST["lastname"])&& !empty($_POST["phone"]) && !empty($_POST["TotalHidden"])){
		Insert_Input();
		$body = file_get_contents("../email/Order.txt");
		$body = AddDataForBody($body);
		$subject    = "Đơn hàng:" . $OrderId;
		$mailto = "nagaoreishi@gmail.com";
		$msg = "";
		SendMail($subject, $body, $mailto, $mailto, $msg);

	}
	
	
function AddDataForBody($body){
	$body = str_replace("--OrderId--",$GLOBALS["OrderId"],$body);
	$body = str_replace("--Date--",$GLOBALS["Date"],$body);
	$body = str_replace("--Payment--",$GLOBALS["Payment"],$body);
	$body = str_replace("--FirstName--",$GLOBALS["FirstName"],$body);
	$body = str_replace("--LastName--",$GLOBALS["LastName"],$body);
	$body = str_replace("--Phone--",$GLOBALS["Phone"],$body);
	$body = str_replace("--Address--",$GLOBALS["Address"],$body);
	$body = str_replace("--City--",$GLOBALS["City"],$body);
	$body = str_replace("--Email--",$GLOBALS["Email"],$body);
	$body = str_replace("--Comment--",$GLOBALS["Comment"],$body);
	$body = str_replace("--SubTotal--",$GLOBALS["SubTotal"],$body);
	$body = str_replace("--DiscountConnect--",$GLOBALS["DiscountConnect"],$body);
	$body = str_replace("--DiscountPayment--",$GLOBALS["DiscountPayment"],$body);
	$body = str_replace("--DiscountValue--",$GLOBALS["DiscountValue"],$body);
	$body = str_replace("--DiscountCoupon--",$GLOBALS["DiscountCouponValue"],$body);
	$body = str_replace("--Total--",$GLOBALS["Total"],$body);
	$body = str_replace("--PriceXL--",$GLOBALS["PriceXL"],$body);
	$body = str_replace("--PriceNT--",$GLOBALS["PriceNT"],$body);
	$body = str_replace("--PriceCC--",$GLOBALS["PriceCC"],$body);
	$body = str_replace("--PriceCK--",$GLOBALS["PriceCK"],$body);
	$body = str_replace("--PriceTP--",$GLOBALS["PriceTP"],$body);
	$body = str_replace("--QuantityXL--",$GLOBALS["XL"],$body);
	$body = str_replace("--QuantityNT--",$GLOBALS["NT"],$body);
	$body = str_replace("--QuantityCC--",$GLOBALS["CC"],$body);
	$body = str_replace("--QuantityCK--",$GLOBALS["CK"],$body);
	$body = str_replace("--QuantityTP--",$GLOBALS["TP"],$body);	
	$AmountXL = number_format(str_replace(".","",$GLOBALS["PriceXL"]) * $GLOBALS["XL"],0,',','.' );
	$AmountNT = number_format(str_replace(".","",$GLOBALS["PriceNT"]) * $GLOBALS["NT"],0,',','.');
	$AmountCC = number_format(str_replace(".","",$GLOBALS["PriceCC"]) * $GLOBALS["CC"],0,',','.');
	$AmountCK = number_format(str_replace(".","",$GLOBALS["PriceCK"]) * $GLOBALS["CK"],0,',','.');
	$AmountTP = number_format(str_replace(".","",$GLOBALS["PriceTP"]) * $GLOBALS["TP"],0,',','.');
	$body = str_replace("--AmountXL--",$AmountXL,$body);
	$body = str_replace("--AmountNT--",$AmountNT,$body);
	$body = str_replace("--AmountCC--",$AmountCC,$body);
	$body = str_replace("--AmountCK--",$AmountCK,$body);
	$body = str_replace("--AmountTP--",$AmountTP,$body);	
	return $body;
	
}



	

	
function Insert_Input(){
	global $CouponCodeHidden, $Date;
	$servername = DATASERVER;
	$username = USER;
	$password = PASSWORD;
	$dbname = DATABASE;
	$sql = "INSERT INTO `order` (`OrderId`, `OrderDate`, `FirstName`, `LastName`, `Telphone`, `Address`, `City`, `Email`, `Connect`, `OrderDetail`, `Payment`, `SubTotal`, `Total`, `Discount1`, `Discount2`, `Discount3`, `Discount4`, `Discount5`, `Comment`, `Status`) VALUES('";
	$sql .= $GLOBALS['OrderId'] . "', '" . $GLOBALS['Date'] . "', '" . $GLOBALS['FirstName'] . "', '";
	$sql .= $GLOBALS['LastName'] . "', '" . $GLOBALS['Phone'] . "', '" .  $GLOBALS['Address'] . "', '";
	$sql .= $GLOBALS['City'] . "', '" . $GLOBALS['Email'] . "'," . $GLOBALS['Connect'] . ", '";
	$sql .= $GLOBALS['OrderDetail'] . "', '" . $GLOBALS['Payment'] . "'," . $GLOBALS['SubTotalHidden'] . ",";
	$sql .= $GLOBALS['TotalHidden'] . "," . $GLOBALS['DiscountConnectHidden'] . "," . $GLOBALS['DiscountPaymentHidden'] . ",";
	$sql .= $GLOBALS['DiscountValueHidden'] . "," . $GLOBALS['DiscountCouponValueHidden'] . ",0, '" . $GLOBALS['Comment'] . "'," . WAIT_FOR_PROCEEDING . ")";
		
	//Disable used Coupon Code
	$sqlCoupon = "UPDATE `coupon` SET `Status`=" . INACTIVE . ", `UsedDate`='$Date' WHERE `Code`='$CouponCodeHidden'";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		//Disable used Coupon Code
		if($CouponCodeHidden != ""){
			$conn->exec($sqlCoupon);
		}

	}
	catch(PDOException $e){
			echo "Error: " . $e->getMessage();
	}
	$conn = null;
}



?>