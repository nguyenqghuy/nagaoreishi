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
	
	<!--Bắt đầu phần thân-->
 	<?php require "php/constant.php"; ?>
	<h1 class="col11">LINH CHI NAGAO CẢM ƠN QUÝ KHÁCH</h1>
	<div class="col11" id="OrderInfo">
		<div class="FirstCol">MÃ ĐƠN HÀNG: </div><div class="LastCol"><?php echo $_GET["OrderId"];?></div>
		<div class="FirstCol">Ngày đặt hàng:</div><div class="LastCol"><?php echo date("d/m/Y");?></div>
		<div class="FirstCol" title="Giá trị hàng sau khi Discount">Giá trị tiền hàng:</div> <div class="LastCol"><?php echo $_GET["Total"];?></div>
		<div class="FirstCol">Thanh toán:</div><div class="LastCol"><?php if($_GET["Payment"]==BANKPAYMENT){echo "Qua ngân hàng";}else{echo "Trực tiếp khi nhận hàng";}?></div>
		<?php 
			if($_GET["Payment"] == BANKPAYMENT){
				$Display = "<div id='BankNote'>Quý khách vui lòng thanh toán chuyển khoản vào một trong những tài khoản ngân hàng sau và ghi <b><span title=" . $_GET["OrderId"] . ">MÃ ĐƠN HÀNG:</span> <span class='Attention'>". $_GET["OrderId"]  . "</span> </b>vào nội dung chuyển tiền.</div>";
				$Display .= "<div>Việc chuyển hàng sẽ được thực hiện ngay sau khi tiền đã được chuyển vào tài khoản. Xin quý khách vui lòng giữ điện thoại trong suốt quá trình nhận hàng.</div>";
				$Display .= "<label id='BankInfo'>Ngân hàng Vietcombank - CN Tân Bình<br />	Số tài khoản: 044 1000 699 334<br /> Chủ TK: Cơ sở SX và Mua bán nấm các loại NAGAO</label>";
				echo $Display;
			}else{
				$Display = "<div id='LastLine'>Chúng tôi sẽ gởi hàng ngay cho quý khách sau khi điện thoại xác nhận lại đơn hàng. Xin quý khách vui lòng giữ điện thoại liên lạc trong suốt quá trình nhận hàng.</div> ";
				echo $Display;
			}
		 ?>
	</div>
	<!--Kết thúc phần thân-->	
<?php include "php/footer.php"; ?>


</body>
</html>
