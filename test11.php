<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php 
date_default_timezone_set("Asia/Ho_Chi_Minh");
   $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('USERNAME','nagao001'));
	//$xmlRoot->appendChild($domtree->createElement('USERNAME','hcmtest'));
	//hcmtest2013
    $pass = "HfvJNrA1oN6354AGhOZ1AvFrRJ8="; 
	//$pass = "LfMUzmQyrXxQtYwgk7VjfU7DOds=";
    $xmlRoot->appendChild($domtree->createElement('PASSWORD', $pass));

    /* get the xml printed */
    $xmlLogin = $domtree->saveXML();
    echo $xmlLogin;
    $url = "http://mkt.vivas.vn:9080/SMSBNAPI/login";
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    //curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlLogin);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    //curl_setopt($ch, CURLOPT_REFERER, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
	$curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        if ($curl_errno > 0) {
                echo "cURL Error ($curl_errno): $curl_error\n";
        } else {
                echo "Data received\n";
        }
    curl_close($ch);
    echo '<br/>' . $ch_result;

//send SMS


$RequestId = 1;
$MsgStr = "heello test";
$Phone = "841636953203";

 /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    $time = date("YmdHis", time());
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('REQID',$RequestId));    
    $xmlRoot->appendChild($domtree->createElement('BRANDNAME', 'NAGAO'));
    $xmlRoot->appendChild($domtree->createElement('TEXTMSG', $MsgStr));
    $xmlRoot->appendChild($domtree->createElement('SENDTIME', $time));
    $xmlRoot->appendChild($domtree->createElement('TYPE', '1'));

    $destination = $domtree->createElement("DESTINATION");
    $destination = $xmlRoot->appendChild($destination);

	
	$MD5Str = "username=nagao001&password=" . $pass . "&brandname=NAGAO&sendtime=$time&msgid=1&msg=$MsgStr&msisdn=$Phone&sharekey=489375";
    $cheksum = md5($MD5Str);
    $destination->appendChild($domtree->createElement('MSGID', '1'));
    $destination->appendChild($domtree->createElement('MSISDN', $Phone));
    $destination->appendChild($domtree->createElement('CHECKSUM', $cheksum));

    /* get the xml printed */
    $xmlPrinted = $domtree->saveXML();
	echo '<br/>' . $xmlPrinted;
    //echo $sessionLogin;
    //$url = "http://mkt.vivas.vn:9080/SMSBNAPI/send_sms";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://mkt.vivas.vn:9080/SMSBNAPI/send_sms"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPrinted);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
	$curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        if ($curl_errno > 0) {
                echo "cURL Error ($curl_errno): $curl_error\n";
        } else {
                echo "Data received\n";
        }
	//Save it to a string result
	echo '<br/>' .  $ch_result;
    curl_close($ch);

?>

</body>
</html>
