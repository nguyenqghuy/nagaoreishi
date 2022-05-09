<?php 
require "../../php/ProcessData.php";
require "../../php/constant.php";
$Variable = $PhoneList =  $MessageForm = "";
if(!empty($_POST["PhoneList"])){$PhoneList = $_POST["PhoneList"];}
if(!empty($_POST["MessageForm"])){$MessageForm = $_POST["MessageForm"];}
if(!empty($_POST["Variable"])){$Variable = $_POST["Variable"];}

$PhoneFile = dirname(__FILE__) . '/../upload/' . $PhoneList;
$MsgFile = dirname(__FILE__) . '/../upload/' . $MessageForm;
$VarFile = dirname(__FILE__) . '/../upload/' . $Variable;
$Time = time();
$SaveFileName = date("YmdHis",$Time) . ".txt";
$SaveFilePath = dirname(__FILE__) . '/../upload/SMSResult/' . $SaveFileName;
$MsgReturn = "";
$Result = "";
$canread = true;
//check can we read file or not
if(!file_exists($PhoneFile) || !file_exists($MsgFile)){
	$canread = false;
}
if(!empty($_POST["Variable"]) && !file_exists($VarFile)){
	$canread = false;
}
//we can read
if($canread){

	$PhoneStr = file_get_contents($PhoneFile);  // Noi dung file Phone
	$ArrPhone = explode("-",$PhoneStr);   //
	$MsgStr = file_get_contents($MsgFile); // Noi dung file message
	
	
	if(!empty($_POST["Variable"])){
		$VarStr = file_get_contents($VarFile);  // noi dung file bien thong so
		$ArrVar = explode("-",$VarStr);
	}
	
	//Đăng nhập hệ thống
	
	$LoginResult = LoginSMS();
	
	$LoginResult = "<RPLY><STATUS>0</STATUS></RPLY>"; //Delete this when execute
	$LoginStatus = ReadResult($LoginResult);
	
	//Đăng nhập thành công
	if(intval($LoginStatus) == 0){
		
		//Create Dom for each Phone
		foreach($ArrPhone as $i=>$Phone){
			$MsgStrTemp = $MsgStr;
			if(!empty($_POST["Variable"])){
				$ArrVarSub = explode(":", $ArrVar[$i]);
				foreach($ArrVarSub as $j => $VarDetail){
					$VarIndex = "--" . $j . "--";
					$MsgStrTemp = str_replace($VarIndex, $VarDetail, $MsgStrTemp);
				}
			}
			
			$RequestID = $Time . $i;
			$SendSMSResult = SendSMS($Phone, $MsgStrTemp, $RequestID);
			$SendSMSResult = "<RPLY><REQID>1039382930</REQID><STATUS>8</STATUS></RPLY>"; //Delete this when execute
			$SendSMSStatus = ReadResult($SendSMSResult);
			if($SendSMSStatus=='3' || $SendSMSStatus =='4' || $SendSMSStatus =='20' || $SendSMSStatus =='98' || $SendSMSStatus =='99' ){
				$MsgReturn = "0*Last Error:" . $ArrSendSMSStatus[intval($SendSMSStatus)];
				break;
			}
			else if($SendSMSStatus!='0'){
				if($MsgReturn==''){$MsgReturn = "2*Light Error; <br />Cannot Send to Phone:<br />";}
				
				$MsgReturn = $MsgReturn . $Phone . ":" . $ArrSendSMSStatus[intval($SendSMSStatus)] . ",<br /> ";
				
			}
		}
		if($MsgReturn == ''){
			$MsgReturn = "1*Success send SMS <br /> ";
		}
		//Log out hệ thống
		
		$LogoutResult = LogoutSMS();
		$LogoutResult = "<RPLY><STATUS>0</STATUS></RPLY>";
		$LogoutStatus = ReadResult($LogoutResult);
		$MsgReturn .= "*Logout Status:" . $ArrLogoutStatus[intval($LogoutStatus)];
		
	}
	else{
		$MsgReturn =  "0*Login SMS system Fail: " . $ArrLoginStatus[intval($LoginStatus)];
	}
	//Send Result XML to Browser;
	$MsgReturn .= "*" . $Result;
	//write the result to a file in upload/smsresult
	 
	$myfile = fopen($SaveFilePath,"w");
	fwrite($myfile, $Result);
	fclose($myfile);
	
}else{ //we cannot read
	$MsgReturn = "0*Cannot Read One of your file";
}
echo $MsgReturn;

?>