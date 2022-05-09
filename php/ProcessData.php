<?php 
function SendMail($subject, $body, $mailto, $mailreply, $msg){
	$mail = new PHPMailer;
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->CharSet="UTF-8";										   
	$mail->Debugoutput = 'html';
	$mail->Host       = MAILSERVER;      // sets GMAIL as the SMTP server
	$mail->SMTPSecure = MAILSECURE;                 // sets the prefix to the servier
	$mail->Port       = MAILPORT;                   // set the SMTP port for the GMAIL server
	
	$mail->SMTPAuth   = MAILAUTH;                  // enable SMTP authentication
	$mail->Username   = MAILUSER;  // GMAIL username
	$mail->Password   = MAILPASS;            // GMAIL password
	
	/*$mail->SetFrom('sale@nagaoreishi.com', 'First Last');
	*/
	$mail->From = MAILUSER;
	$mail->FromName = 'NAGAO REISHI';
	
	$mail->AddReplyTo($mailreply,$mailreply);
	
	$address = $mailto;
	$mail->AddAddress($address, "NAGAO");
	
	$mail->Subject    = $subject;
	$mail->isHTML(true);
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($body);
	
	
	/*$mail->AddAttachment("images/phpmailer.gif");      // attachment
	$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	*/
	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo $msg;
	}
	

}


function GetLastId(){
	$servername = DATASERVER;
	$username = USER;
	$password = PASSWORD;
	$dbname = DATABASE;
	$sql = "select max(`ID`) from `order`";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare($sql);	
		$stmt->execute();
		$result = $stmt->fetchall();
		$LastID =  $result[0][0];
		
	}
	catch(PDOException $e)	{
			echo "Error: " . $e->getMessage();
	}
	$conn = null;
	return $LastID + 1;
}


function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

function LoginSMS(){
	 global $Result;
 /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('USERNAME',USERSMS));
	//hcmtest2013
    $xmlRoot->appendChild($domtree->createElement('PASSWORD', PASSSMS));

    /* get the xml printed */
    $xmlPrinted = $domtree->saveXML();
	$Result .=  $xmlPrinted; 
	$Result .=  "\r\n";
    
    //$url = "http://mkt.vivas.vn:9080/SMSBNAPI/login";
	
  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, URLLOGIN); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPrinted);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
	//Save it to a string result
	$Result .=  $ch_result; 
	$Result .=  "\r\n";
	
    curl_close($ch);
    return $ch_result;
}


function ReadResult($ResultStr){
	$domtree = new DOMDocument();
	$domtree->loadXML($ResultStr);
	$Status = $domtree->getElementsByTagName("STATUS")->item(0);
	return $Status->nodeValue;
	
}

function SendSMS($Phone, $MsgStr, $RequestId){
 global $Result;
 /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    $time = date("YmdHis", time());
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('REQID',$RequestId));    
    $xmlRoot->appendChild($domtree->createElement('BRANDNAME', BRANDNAME));
    $xmlRoot->appendChild($domtree->createElement('TEXTMSG', $MsgStr));
    $xmlRoot->appendChild($domtree->createElement('SENDTIME', $time));
    $xmlRoot->appendChild($domtree->createElement('TYPE', TYPESMS));

    $destination = $domtree->createElement("DESTINATION");
    $destination = $xmlRoot->appendChild($destination);

	
	$MD5Str = "username=" . USERSMS . "&password={" . PASSSMS . "}&brandname=" . BRANDNAME . "&sendtime={$time}&msgid=1&msg=$MsgStr&msisdn=$Phone&sharekey=" . SHAREKEY;
    $cheksum = md5($MD5Str);
    $destination->appendChild($domtree->createElement('MSGID', '1'));
    $destination->appendChild($domtree->createElement('MSISDN', $Phone));
    $destination->appendChild($domtree->createElement('CHECKSUM', $cheksum));

    /* get the xml printed */
    $xmlPrinted = $domtree->saveXML();
	$Result .=  $xmlPrinted; 
	$Result .=  "\r\n";
    //echo $sessionLogin;
    //$url = "http://mkt.vivas.vn:9080/SMSBNAPI/send_sms";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, URLSENDSMS); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPrinted);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
	//Save it to a string result
	$Result .=  $ch_result; 
	$Result .=  "\r\n";

    curl_close($ch);
    return $ch_result;
}

function LogoutSMS(){
    global $Result;
	//$url = "http://mkt.vivas.vn:9080/SMSBNAPI/logout";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, URLLOGOUT); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
    curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPrinted);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
	//Save it to a string result
	$Result .=  $ch_result; 
	$Result .=  "\r\n";

    curl_close($ch);
    return $ch_result;
}

?>