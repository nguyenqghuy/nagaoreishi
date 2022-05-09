<?php    
//login	
 /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('USERNAME','hcmtest'));
	//hcmtest2013
    $pass = "LfMUzmQyrXxQtYwgk7VjfU7DOds="; 
    $xmlRoot->appendChild($domtree->createElement('PASSWORD', $pass));

    /* get the xml printed */
    $xmlLogin = $domtree->saveXML();
    
    $url = "http://mkt.vivas.vn:9080/SMSBNAPI/login";
	
  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlLogin);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
    curl_close($ch);
    echo $ch_result;
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//send sms
 /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');

    /* create the root element of the AmazonEnvelope tree */
    $xmlRoot = $domtree->createElement("RQST");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    $time = date("YmdHis", time());
    /* you should enclose the following two lines in a cicle */
    $xmlRoot->appendChild($domtree->createElement('REQID','1'));    
    $xmlRoot->appendChild($domtree->createElement('BRANDNAME', "VNPT.Tech"));
    $xmlRoot->appendChild($domtree->createElement('TEXTMSG', "Test"));
    $xmlRoot->appendChild($domtree->createElement('SENDTIME', $time));
    $xmlRoot->appendChild($domtree->createElement('TYPE', 1));

    $destination = $domtree->createElement("DESTINATION");
    $destination = $xmlRoot->appendChild($destination);

	
	//echo "</br>" . "pass=". $pass . "</br>";
    $cheksum = md5("username=hcmtest&password={$pass}&brandname=VNPT.Tech&sendtime={$time}&msgid=12345613&msg=Test&msisdn=84942554233&sharekey=123456");
    $destination->appendChild($domtree->createElement('MSGID', 12345613));
    $destination->appendChild($domtree->createElement('MSISDN', '84942554233'));
    $destination->appendChild($domtree->createElement('CHECKSUM', $cheksum));

    /* get the xml printed */
    $xmlLogin = $domtree->saveXML();
    //echo $sessionLogin;
    $url = "http://mkt.vivas.vn:9080/SMSBNAPI/send_sms";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8'));
    /* curl_setopt($ch, CURLOPT_HEADER, 0);*/
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlLogin);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    /*curl_setopt($ch, CURLOPT_REFERER, '');*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $ch_result = curl_exec($ch);
    curl_close($ch);
    echo $ch_result;