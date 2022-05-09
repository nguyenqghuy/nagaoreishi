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
mysqli_close($db);
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

if($db){	
	try{
		$stmt = $db->prepare("SELECT max_gest_news, formato_data FROM `$tab_config`");	
		$stmt->execute();
		$rowconf = $stmt->fetchall();
	}
	catch(PDOException $e)	{
		$query_msg = "Could not get config: " . $e->getMessage();
	}
/*	$conf = mysqli_query($db, "SELECT max_gest_news, formato_data FROM `$tab_config`");
	$rowconf = mysqli_fetch_array($conf);
*/	
	
	// risultati visualizzati per pagina (serve per la paginazione)
	$rec_page = $rowconf[0]['max_gest_news'];
	$start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;
	
	
	$get_sortby = NULL;
	$order_query = "SELECT nt.ID, nt.Code, nt.FromDate, nt.ToDate, nt.Status, nt.Discount, nt.CreatedDate, nt.UsedDate FROM `coupon` nt ORDER BY nt.CreatedDate DESC LIMIT $start,$rec_page";
	
	if (isset($_POST['submit_sel'])) {
			if(isset($_POST['cb_id'])){
				$nid = implode(",", $_POST['cb_id']);
			}
			//cancello le news
			if ($_POST['submit_sel'] == 'DeleteCode' && isset($_POST['cb_id'])) {
				try{
					$db->exec("DELETE FROM `coupon` WHERE ID IN ($nid)");
					$query_msg = '<div id="success">Delete Code successfully</div><br />';
				}catch(PDOException $e){
					$query_msg = '<div id="error">Cannot Delete Code:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
				}
			}
	
			//Disable Code
			if ($_POST['submit_sel'] == 'DisableCode' && isset($_POST['cb_id'])) {
				$DisableDate = date("Y-m-d H:i:s");
				try{
					$db->exec("UPDATE `coupon` SET Status=0, UsedDate='$DisableDate' WHERE ID IN ($nid)");
					$query_msg = '<div id="success">Disable Code successfully</div><br />';
				}catch(PDOException $e){
					$query_msg = '<div id="error">Cannot Disable Code:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
				}
				
			}
	
			
			if ($_POST['submit_sel'] == 'GenerateCode'){
				$NumCode = abs(floor(intval($_POST['NumCode'])));
				$FromDate = $_POST['FromDate'];
				$ToDate = $_POST['ToDate'];
				$Discount = abs(floor(intval($_POST['Discount'])));
				$CreatedDate = date("Y-m-d H:i:s");
				$sql = '';
				try{
					$db->beginTransaction();
					for($j = 0; $j < $NumCode; $j++){
					 
						 $Code = '';
						 for ($i = 0; $i < $random_string_length; $i++) {
							  $Code .= $characters[rand(0, strlen($characters) - 1)];
						 }
						 $sql = "INSERT INTO `coupon` (`Code`, `FromDate`, `ToDate`, `CreatedDate`, `Discount`) VALUES('$Code', '$FromDate', '$ToDate', '$CreatedDate', $Discount)";
						 $db->exec($sql);
					}
					$db->commit();
					$query_msg = '<div id="success">Generate Codes successfully</div><br />';
				}
				catch(PDOException $e){
					'<div id="error">Cannot Generate Code:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
				}
				
			}
	
			
	}
}	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">     
	  <head>         
		<title>Manage Coupon
		</title>         
		<link rel="stylesheet" href="../style.css" type="text/css" />		 
	<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>      
	  </head>     
	  <body>
	<?php
	require_once ("menu.php");
	echo $query_msg;
	echo $popup_autore;
	?>
	<form name="admin" action="couponcode.php" method="post">
	<table width="100%" style="border: 3px solid #DDDDDD;" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF" align="center">
	<tr><td width="1%" align="center" bgcolor="#EEEEEE"><img src="<?php echo $img_path; ?>/news.png" border="0" alt="" /></td>
	<td width="18%" class="text" align="center" bgcolor="#EEEEEE"> Code </td>
	<td width="10%" class="text" align="center" bgcolor="#EEEEEE">From Date</td>
	<td width="12%" class="text" align="center" bgcolor="#EEEEEE">To Date</td>
	<td width="6%" class="text" align="center" bgcolor="#EEEEEE">Status</td>
	<td width="7%" class="text" align="center" bgcolor="#EEEEEE">Discount</td>
	<td width="6%" class="text" align="center" bgcolor="#EEEEEE">Created Date</td>
	<td width="6%" class="text" align="center" bgcolor="#EEEEEE">Used Date</td>
	</tr>
	<?php
	try{	
		$stmt = $db->prepare($order_query);
		$stmt->execute();
		$q_order = $stmt->fetchall();
	/*	$q_order = mysqli_query($db, "$order_query");
	*/	
		/*while ($q_riga = mysqli_fetch_array($q_order)) {*/
		foreach($q_order as $key=>$q_riga){
			
			echo '<tr onmouseover="this.bgColor=\'#E6F1FA\'" onmouseout="this.bgColor=\'#FFFFFF\'">
							  <td align="center"><input type="checkbox" name="cb_id[]" value="' . $q_riga['ID'] . '" id="coupon_' . $q_riga['ID'] . '" /></td>
							  <td align="left" class="text"><label for="coupon_' . $q_riga['ID'] . '">' . $q_riga['Code'] . '</label></td>
							  <td align="left" class="text">' . $q_riga['FromDate'] . '</td>
							  <td align="left" class="text">' . $q_riga['ToDate'] . '</td>
							  <td align="center" class="text">' . $q_riga['Status'] . '</td>
							  <td align="center" class="text">' . $q_riga['Discount'] . '%</td>
							  <td align="left" class="text">' . $q_riga['CreatedDate'] . ' </td>
							  <td align="center" class="text">' . $q_riga['UsedDate'] . ' </td>
				   </tr>';
		}
	}
	catch(PDOException $e){
		echo "Cannot get Data to display:" . $e->getMessage();
	}
?>	
	<tr>
	  <td colspan="5" bgcolor="#EEEEEE" class="text2" align="left">
	Select <a href="javascript:onClick=checkTutti()" class="piccolo">all</a>, <a href="javascript:onClick=uncheckTutti()" class="piccolo">none</a>&nbsp;
	<select name="submit_sel" onchange="return dropdownCoupon(this);">
		<option selected="selected">Operation</option>
		<option value="DeleteCode" style="background:red; color:white;"> Delete Code </option>
		<option value="DisableCode" style="background:red; color:white;"> Disabled Code </option>
		<option value="GenerateCode"> Generate Code </option>
	</select> 
	Number of Codes: <input type="textbox" name="NumCode" value="20" pattern="[0-9]" />
	From Date(2015-08-19 17:48:23): <input type="textbox" name="FromDate" value="<?php echo date("Y-m-d H:i:s")?>" />
	To Date: <input type="textbox" name="ToDate" value="<?php echo date("Y-m-d H:i:s")?>"/>
	Discount(%): <input type="textbox" name="Discount" value="20" pattern="[0-9]" />
	</td>
	<td colspan="3" bgcolor="#EEEEEE" class="text2" align="right">
<?php	
	//paginazione
	$query_count = "SELECT COUNT(id) AS NumTotale FROM `coupon`";
	try{
		$stmt = $db->prepare($query_count);
		$stmt->execute();
		$num_totale_riga = $stmt->fetchall();
	}
	catch(PDOException $e){
		echo "Cannot get Numtotal:" . $e->getMessage();
	}

	$numero_pagine = ceil($num_totale_riga[0]['NumTotale'] / $rec_page);
	$pagina_attuale = ceil(($start / $rec_page) + 1);
	echo '<b>(' . $lang['totale'] . ' ' . $num_totale_riga[0]['NumTotale'] . ')</b> ' . page_bar("couponcode.php?", $pagina_attuale, $numero_pagine, $rec_page);
	echo '</td></tr></table>';

	?>         
    </form><br />         
    <?php require_once ("footer.php"); $db = NULL; ?>      
  </body>
</html>