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
require_once (dirname(__FILE__) . '/../../php/ProcessData.php');

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
		//set info for sending SMS
		$RequestID = time();
		$Result = "";
		
		$MsgSMS = str_replace("--0--",$_POST["OrderId"], $OrderSMS[intval($Value)]);
		
		try{
			$db->exec("UPDATE `order` SET `Status`=$Value WHERE `ID`=$IDOrder");
			//send SMS
			$LoginStatus = $SendSMSStatus = $LogoutStatus = "";
			$LoginResult = LoginSMS();
			if(!empty($LoginResult)){ $LoginStatus = ReadResult($LoginResult);}
			if(!empty($LoginResult) && intval($LoginStatus) == 0){
				$SendSMSResult = SendSMS($_POST["SendPhone"], $MsgSMS, $RequestID);
				if(!empty($SendSMSResult)){$SendSMSStatus = ReadResult($SendSMSResult);}
				
			}
			$LogoutResult = LogoutSMS();
			if(!empty($LogoutResult)){$LogoutStatus = ReadResult($LogoutResult);}
			
			$query_msg = '<div id="success">Update Status successfully<br/>Login SMS Status:' .  $ArrLoginStatus[$LoginStatus] . ' - ' . $LoginResult . '<br/>';
			if(intval($LoginStatus) == 0){
				$query_msg .= 'Send SMS Status:' . $ArrSendSMSStatus[$SendSMSStatus] . '</br>';
			}
			$query_msg .= 'Log out SMS Status:' . $ArrLogoutStatus[$LogoutStatus] . ' - ' . $LogoutResult . '<br />';
			$query_msg .= '<textarea>' . $Result . '</textarea></div><br/>';
		}catch(PDOException $e){
			$query_msg = '<div id="error">Cannot Update Status:</div><br /><span class="text2">' . $e->getMessage() . '</span><br /><br />';
		}
	}
			
	}
}	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">     
	  <head>         
		<title>Manage Order
		</title>         
		<link rel="stylesheet" href="../style.css" type="text/css" />
		<link rel="stylesheet" href="css/order.css" type="text/css" />		 
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>	 
	<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>
	<script src="javascript/order.js"></script>        
	  </head>     
	  <body>
	<?php
	require_once ("menu.php");
	echo $query_msg;
	echo $popup_autore;
	?>
	<form name="admin" action="Order.php" method="post">
	<div id="OrderContainer">
		<div>
			<div>
				<img src="<?php echo $img_path; ?>/news.png" border="0" alt="" />		
			</div>
			<div>Order ID</div>
			<div>Date</div>
			<div>Name</div>
			<div>Phone</div>
			<div>Order Detail</div>
			<div>Payment</div>
			<div>Total</div>
			<!--<div>Comment</div>-->
			<div>Status</div>
		</div>
	<?php
	try{	
		$stmt = $db->prepare($order_query);
		$stmt->execute();
		$q_order = $stmt->fetchall();
		foreach($q_order as $key=>$q_riga){
?>			
		<div>
			<div>
				<input type="checkbox" name="cb_id[]" value="<?php echo $q_riga['ID']; ?>" id="coupon_<?php echo $q_riga['ID']; ?>" />
			</div>
			<div>
				<label id="OrderId_<?php echo $q_riga['ID']; ?>"><?php echo $q_riga['OrderId']; ?></label>
				<div>
					<p><b>Order ID:</b> <span> <?php echo $q_riga['OrderId']; ?></span> </p>
					<p><b>Date:</b> <span> <?php echo $q_riga['OrderDate']; ?></span> </p>
					<p><b>Name:</b> <span> <?php echo $q_riga['LastName'] . " " . $q_riga['FirstName']; ?></span> </p>
					<p><b>Phone:</b> <span> <?php echo $q_riga['Telphone']; ?>&nbsp;</span> </p>
					<p><b>Address:</b> <span> <?php echo $q_riga['Address']; ?>&nbsp;</span> </p>
					<p><b>City:</b> <span> <?php echo $q_riga['City']; ?>&nbsp;</span> </p>
					<p><b>Email:</b> <span> <?php echo $q_riga['Email']; ?>&nbsp;</span> </p>
					<p><b>Connect:</b> <span> <?php echo $q_riga['Connect']; ?></span> </p>
					<p><b>Order Detail :</b> <span> <?php echo $q_riga['OrderDetail']; ?>&nbsp;</span> </p>
					<p><b>Payment:</b> <span> <?php echo $q_riga['Payment']; ?>&nbsp;</span> </p>
					<p><b>Subtotal:</b> <span> <?php echo number_format($q_riga['SubTotal'],0,',','.'); ?>&nbsp;</span> </p>
					<p><b>Discount1:</b> <span> <?php echo $q_riga['Discount1']; ?></span> </p>
					<p><b>Discount2:</b> <span> <?php echo $q_riga['Discount2']; ?></span> </p>
					<p><b>Discount3:</b> <span> <?php echo $q_riga['Discount3']; ?></span> </p>
					<p><b>Discount4:</b> <span> <?php echo $q_riga['Discount4']; ?></span> </p>
					<p><b>Discount5:</b> <span> <?php echo $q_riga['Discount5']; ?></span> </p>
					<p><b>Total:</b> <span> <?php echo number_format($q_riga['Total'],0,',','.'); ?></span> </p>
					<p><b>Comment:</b> <span> <?php echo $q_riga['Comment']; ?>&nbsp;</span> </p>
					<p><b>Status:</b> <span> <?php echo $StatusOrder[intval($q_riga['Status'])] ?></span> </p>
					
					
				</div>		
			</div>
			<div><?php echo $q_riga['OrderDate']; ?></div>
			<div><?php echo $q_riga['LastName'] . " " . $q_riga['FirstName']; ?></div>
			<div><label id=Phone_<?php echo $q_riga['ID']; ?>><?php echo $q_riga['Telphone']; ?></label> - <a href="#" class="EditPhone">Edit</a>
				<div><input type="text" id="EditPhone_<?php echo $q_riga['ID']; ?>" /> <a href="javascript:EditPhone('<?php echo $q_riga['ID']; ?>');">Update</a></div>
			</div>
			<div><?php echo $q_riga['OrderDetail']; ?>&nbsp;</div>
			<div><?php echo $q_riga['Payment']; ?>&nbsp;</div>
			<div><?php echo number_format($q_riga['Total'],0,',','.'); ?></div>
			
			<div><?php echo $StatusOrder[intval($q_riga['Status'])] ?>
				<select name="Status_<?php echo $q_riga['ID']; ?>" onchange="ChangeStatus(this);">
							  		
									<option selected>Change Status</option>
									<?php if($q_riga['Status']==0){  ?>
									<option value="<?php echo WAIT_FOR_MONEY; ?>"> <?php echo $StatusOrder[WAIT_FOR_MONEY];?> </option>
									<option value="<?php echo RECEIVED_MONEY; ?>"> <?php echo $StatusOrder[RECEIVED_MONEY];?></option>
									<option value="<?php echo TRANSFERED; ?>"> <?php echo $StatusOrder[TRANSFERED];?></option>
									<?php  }elseif($q_riga['Status']==1){ ?>
									<option value="<?php echo RECEIVED_MONEY; ?>"> <?php echo $StatusOrder[RECEIVED_MONEY];?></option>
									<option value="<?php echo TRANSFERED; ?>"> <?php echo $StatusOrder[TRANSFERED];?></option>
									<?php  }elseif($q_riga['Status']==2){?>
									<option value="<?php echo TRANSFERED; ?>"> <?php echo $StatusOrder[TRANSFERED];?></option>
									<?php  } ?>
				</select>		
			</div>
		</div>
	
		

<?php	} 

	}
	catch(PDOException $e){
		echo "Cannot get Data to display:" . $e->getMessage();
	}
?>	
	</div>
	<div>
		<div>
			Select <a href="javascript:onClick=checkTutti()" class="piccolo">all</a>, <a href="javascript:onClick=uncheckTutti()" class="piccolo">none</a>&nbsp;
			<select name="submit_sel" onchange="return dropdownOrder(this);">
				<option selected="selected">Operation</option>
				<option value="DeleteOrder" style="background:red; color:white;"> Delete Order </option>
			</select> 
		</div>
		<div>
<?php	
			//paginazione
			$query_count = "SELECT COUNT(ID) AS NumTotale FROM `order`";
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
			echo '<b>(' . $lang['totale'] . ' ' . $num_totale_riga[0]['NumTotale'] . ')</b> ' . page_bar("Order.php?", $pagina_attuale, $numero_pagine, $rec_page);


	?>  </div>
	</div>	
	<input type="hidden" name="OrderId"/>
	<input type="hidden" name="SendPhone"/>
	<input type="hidden" name="NameStatus"/>   
    </form><br />         
    <?php require_once ("footer.php"); ?>      
  </body>
</html>