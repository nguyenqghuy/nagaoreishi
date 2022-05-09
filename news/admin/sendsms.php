<?php

session_start();
header('Content-type: text/html; charset=utf-8');

//calcolo il tempo di generazione della pagina (1a parte)
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];

//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/functions.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

require_once (dirname(__FILE__) . '/../../php/constant.php');

//$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);
$db = mysqli_connect(DATASERVER, USER, PASSWORD, DATABASE);
check_login();
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

if ($_SESSION['livello_id'] != 1) {
		header('Location: ' . $dir_admin . '/elenco_news.php');
		exit();
}

/*if($db){	
	try{
		$stmt = $db->prepare("SELECT max_gest_news, formato_data FROM `$tab_config`");	
		$stmt->execute();
		$rowconf = $stmt->fetchall();
	}
	catch(PDOException $e)	{
		$query_msg = "Could not get config: " . $e->getMessage();
	}

	// risultati visualizzati per pagina (serve per la paginazione)
	$rec_page = $rowconf[0]['max_gest_news'];
	$start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;
	
	
	$get_sortby = NULL;
	$order_query = "SELECT * FROM `order` ORDER BY `OrderDate` DESC LIMIT $start,$rec_page";
	
	if (isset($_POST['submit_sel'])) {
			if(isset($_POST['cb_id'])){
				$nid = implode(",", $_POST['cb_id']);
			}
			//cancello le news
			if ($_POST['submit_sel'] == 'DeleteOrder' && isset($_POST['cb_id'])) {
				try{
					$db->exec("DELETE FROM `order` WHERE ID IN ($nid)");
					$query_msg = '<div id="success">Delete Order successfully</div><br />';
				}catch(PDOException $e){
					$query_msg = '<div id="error">Cannot Delete Code:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
				}
			}
	if($_POST['NameStatus'] != ""){
		$ElementName = $_POST['NameStatus'];
		$IDEStr = explode("_",$ElementName);
		$IDOrder = $IDEStr[1];
		$Value = $_POST[$ElementName];
		try{
			$db->exec("UPDATE `order` SET `Status`=$Value WHERE `ID`=$IDOrder");
			$query_msg = '<div id="success">Update Status successfully</div><br />';
		}catch(PDOException $e){
			$query_msg = '<div id="error">Cannot Update Status:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
		}
	}
			
	}
}*/	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">     
	  <head>         
		<title>Send SMS		</title>         
		<link rel="stylesheet" href="../style.css" type="text/css" />
		<link rel="stylesheet" href="css/sendsms.css" type="text/css" />
		 
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>	 
			<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>
			<script language="JavaScript" src="javascript/sendsms.js" type="text/JavaScript"></script>
	  </head>     
	  <body>
	<?php
	require_once ("menu.php");
	echo $query_msg;
	echo $popup_autore;
	?>
	<h1>Send SMS</h1>
	<form name="admin" action="sendsms.php" method="post">
	<div id="Container">
		Phone List File: </br>
		<input type="text" name="PhoneList" /></br>
		Message Form File: </br>
		<input type="text" name="MessageForm" /></br>
		Variable File: </br>
		<input type="text" name="Variable" /></br></br>
		<input type="button" name="Send" value="Send" onclick="SendSMS();" />
		
	</div><br /><br />
	<div id="Msg01"></div>
	<br />
	
	<div>
		Phone List has the following format: 84983923748-849837283949-849382718958<br />
		Message Form File has the format: Ma don hang --1-- cua quy khach --2-- da duoc goi di <br/>
		Variable File has the following format: variable1:variable2:variable3-variable1:variable2:variable3-variable1:variable2:variable3
		
	</div>
	<br/>
		<textarea id="Msg02"></textarea>
    </form><br />         
    <?php require_once ("footer.php"); ?>      
  </body>
</html>