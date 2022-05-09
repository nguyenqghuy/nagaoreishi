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
<?php require_once (dirname(__FILE__) . '/../config.php'); ?>
<div id="OrderContainer">
	<div>
		<div>
			<img src="<?php echo $img_path; ?>/news.png" border="0" alt="" />		</div>
		<div>Order ID</div>
		<div>Date</div>
		<div>Name</div>
		<div>Phone</div>
		<div>Order Detail</div>
		<div>Payment</div>
		<div>Total</div>
		<div>Comment</div>
		<div>Status</div>
	</div>

	<div>
		<div>
		</div>
		<div>
			Order ID
			<div>
				<p><b>Order ID:</b> <span> Id</span> </p>
				<p><b>Date:</b> <span> Date</span> </p>
				<p><b>Name:</b> <span> Name</span> </p>
				<p><b>Phone:</b> <span> Phone</span> </p>
				<p><b>Address:</b> <span> Address</span> </p>
				<p><b>City:</b> <span> City</span> </p>
				<p><b>Email:</b> <span> Email</span> </p>
				<p><b>Connect:</b> <span> Connect</span> </p>
				<p><b>Order Detail :</b> <span> Order detail</span> </p>
				<p><b>Payment:</b> <span> payment</span> </p>
				<p><b>Subtotal:</b> <span> Subtotal</span> </p>
				<p><b>Discount1:</b> <span> Discount1</span> </p>
				<p><b>Discount2:</b> <span> Discount2</span> </p>
				<p><b>Discount3:</b> <span> Discount3</span> </p>
				<p><b>Discount4:</b> <span> Discount4</span> </p>
				<p><b>Discount5:</b> <span> Discount5</span> </p>
				<p><b>Total:</b> <span> Total</span> </p>
				<p><b>Comment:</b> <span> Comment</span> </p>
				<p><b>Status:</b> <span> status</span> </p>
				
				
			</div>		
		</div>
		<div>Date</div>
		<div>Name</div>
		<div>Phone - <a class="EditPhone">Edit</a>
			<div><input type="text" name="EditPhone_ID" /> <a href="javascript:EditPhone('ID');">Update</a></div>
		</div>
		<div>Order Detail</div>
		<div>Payment</div>
		<div>Total</div>
		<div>Comment</div>
		<div>Status</div>
	</div>
	
</div>
<div>
	<div>select here</div>
	<div>Page here</div>
</div>

</body>
</html>

