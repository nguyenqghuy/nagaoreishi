<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Xac nhan don hang nam linh chi NAGAO</title>
<link rel="alternate" href="http://www.namlinhchinagao.com/thong-bao-don-hang/" hreflang="vi-vn" />
<meta name="description" content="Linh Chi NAGAO xin chan thanh cam on quy khach, chuc quy khach suc khoe doi dao" />
<link rel="canonical" href="http://www.namlinhchinagao.com/thong-bao-don-hang/" />
<meta http-equiv="content-language" content="vi"/>
<meta property="og:locale" content="vi_VN" />
<meta property="og:url" content="http://www.namlinhchinagao.com/thong-bao-don-hang/" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Xac nhan don hang Linh Chi NAGAO" />
<meta property="og:description" content="Linh Chi NAGAO xin chan thanh cam on quy khach, chuc quy khach suc khoe doi dao" />
<link rel="shortcut icon" href="http://www.namlinhchinagao.com/picture/linhchiNAGAOICON.jpg" />

<meta name="robots" content="noindex, nofollow" />

<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/Thongbaodonhang.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/common.js" type="text/javascript"> </script>

</head>

<body>
<?php include "php/header.php"; ?>
<!-- Google Code for Mua hang Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 947126149;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "RSycCO6v2mIQhf_PwwM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/947126149/?label=RSycCO6v2mIQhf_PwwM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
	
	<!--B???t ?????u ph???n th??n-->
 	<?php require "php/constant.php"; ?>
	<h1 class="col11">LINH CHI NAGAO C???M ??N QU?? KH??CH</h1>
	<div class="col11" id="OrderInfo">
		<div class="FirstCol">M?? ????N H??NG: </div><div class="LastCol"><?php echo $_GET["OrderId"];?></div>
		<div class="FirstCol">Ng??y ?????t h??ng:</div><div class="LastCol"><?php echo date("d/m/Y");?></div>
		<div class="FirstCol" title="Gi?? tr??? h??ng sau khi Discount">Gi?? tr??? ti???n h??ng:</div> <div class="LastCol"><?php echo $_GET["Total"];?></div>
		<div class="FirstCol">Thanh to??n:</div><div class="LastCol"><?php if($_GET["Payment"]==BANKPAYMENT){echo "Qua ng??n h??ng";}else{echo "Tr???c ti???p khi nh???n h??ng";}?></div>
		<?php 
			if($_GET["Payment"] == BANKPAYMENT){
				$Display = "<div id='BankNote'>Qu?? kh??ch vui l??ng thanh to??n chuy???n kho???n v??o m???t trong nh???ng t??i kho???n ng??n h??ng sau v?? ghi <b><span title=" . $_GET["OrderId"] . ">M?? ????N H??NG:</span> <span class='Attention'>". $_GET["OrderId"]  . "</span> </b>v??o n???i dung chuy???n ti???n.</div>";
				$Display .= "<div>Vi???c chuy???n h??ng s??? ???????c th???c hi???n ngay sau khi ti???n ???? ???????c chuy???n v??o t??i kho???n. Xin qu?? kh??ch vui l??ng gi??? ??i???n tho???i trong su???t qu?? tr??nh nh???n h??ng.</div>";
				$Display .= "<label id='BankInfo'>Ng??n h??ng Vietcombank - CN T??n B??nh<br />	S??? t??i kho???n: 044 1000 699 334<br /> Ch??? TK: C?? s??? SX v?? Mua b??n n???m c??c lo???i NAGAO</label>";
				echo $Display;
			}else{
				$Display = "<div id='LastLine'>Ch??ng t??i s??? g???i h??ng ngay cho qu?? kh??ch sau khi ??i???n tho???i x??c nh???n l???i ????n h??ng. Xin qu?? kh??ch vui l??ng gi??? ??i???n tho???i li??n l???c trong su???t qu?? tr??nh nh???n h??ng.</div> ";
				echo $Display;
			}
		 ?>
	</div>
	<!--K???t th??c ph???n th??n-->	
<?php include "php/footer.php"; ?>


</body>
</html>
