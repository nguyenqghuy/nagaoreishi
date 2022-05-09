<?php 
		
		require "PHPMailer-master/PHPMailerAutoload.php";
		require "PHPMailer-master/class.smtp.php";
		require "constant.php";
		require "TBDH.php";
		$strredirect = "Location: /thong-bao-don-hang/" . $OrderId . "/" . $Total . "/" . $Payment . "/";
		header($strredirect);
		//header("Refresh: 0; url=/thong-bao-don-hang/" . $OrderId . "/" . $Total . "/" . $Payment . "/");
		exit();	
?>