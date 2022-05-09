<?php



//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/functions.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

require_once (dirname(__FILE__) . '/../../php/constant.php');

//$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);
$db = mysqli_connect(DATASERVER, USER, PASSWORD, DATABASE);
//check_login();
$db = NULL;
$servername = DATASERVER;
$dbname = DATABASE;
$query_msg = NULL;
$popup_autore = NULL;
try{
	$db = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)	{
	$query_msg = "Could not connect: " . $e->getMessage();
}
	//se non sono un amministratore e voglio visualizzare questa pagina, redirigo all'elenco news personale


if($db){	
	try{
		$stmt = $db->prepare("SELECT * FROM `news_testi` WHERE `friendly_url` = '' ");	
		$stmt->execute();
		$Rows = $stmt->fetchall();
		foreach($Rows as $RowEUrl){
			$new_friendly_url = url_slug($RowEUrl['titolo']);
			$intial_friendly_url = $new_friendly_url;
			$counter = 1;
			$keepchecking = true;
			# while we not found unique friendly url
			while($keepchecking){
				$stmt = $db->prepare("SELECT * FROM `news_testi` WHERE `friendly_url` = '$new_friendly_url' ");	
				$stmt->execute();
				$RowsCheck = $stmt->fetchall();
				if(count($RowsCheck)>0){
					$counter++;  
			
					# we reapeat this until url-2 url-3 url-4..... until we find not used url for articles
					$new_friendly_url = $intial_friendly_url . '-' .  $counter;
				}else{
					$keepchecking = false;
				}
			}#end while
			$db->exec("UPDATE `news_testi` SET `friendly_url` = '$new_friendly_url' WHERE `id` = '{$RowEUrl['id']}'");
		}#end for
	}
	catch(PDOException $e)	{
		$query_msg = "Could not get news: " . $e->getMessage();
	}
	$db=NULL;
}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">     
	  <head>         
		<title>Create friendly url at the beginning when friendurl is still empty</title>         
		<link rel="stylesheet" href="../style.css" type="text/css" />		 
	  </head>     
	  <body>
	  everything is done!
  </body>
</html>