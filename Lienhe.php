<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lien he nam linh chi NAGAO</title>
<link rel="alternate" href="http://www.namlinhchinagao.com/lien-he/" hreflang="vi-vn" />
<meta name="description" content="Lien he nam linh chi NAGAO de duoc tu van ve san pham, cach su dung nam linh chi." />
<link rel="canonical" href="http://www.namlinhchinagao.com/lien-he/" />
<meta http-equiv="content-language" content="vi">
<meta property="og:locale" content="vi_VN" />
<meta property="og:url" content="http://www.namlinhchinagao.com/lien-he/" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Lien he nam linh chi NAGAO" />
<meta property="og:description" content="Lien he nam linh chi NAGAO de duoc tu van ve san pham, cach su dung nam linh chi." />
<meta property="og:image" content="http://www.namlinhchinagao.com/picture/linhchi-nagao.jpg" />

<meta name="robots" content="index,follow" />

<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/Lienhe.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/javascript/common.js"> </script>
<script src="/javascript/lienhe.js"> </script>
</head>

<body>
<?php include "php/header.php"; ?>
	
	<!--Phần thân-->
	<div id="Breadcum" class="col11">
		<a href="/ho-tro/" title="Ve trang ho tro " id="ho-tro01">Hỗ trợ</a> ► <a>Liên hệ</a>
	</div>
	<h1>LIÊN HỆ</h1>
	<div class="col10" id="LHContainer">
		<div id="ColLeft">
			<p><img src="/picture/logoLinhchiNagao.jpg" alt="logo nam linh chi NAGAO" width="20" /> NAGAO</p>
			<p>Bán hàng và hỗ trợ:</p>
			<p>+84 (0)901 466 842</p>
			<p><a href="mailto:nagaoreishi@gmail.com">nagaoreishi@gmail.com</a></p>
			<p>2364 Xã Long Chánh</p>
			<p>TX Gò Công</p>
			<p>Tiền GIang</p>
			<p>Việt Nam</p>
			<p>Xã hội</p>
			<p><a href="https://www.facebook.com/Linh.Chi.Nagao" target="_blank" title="Facebook Fan page cua nam linh chi NAGAO" id="facebook01">facebook.com/Linh.Chi.Nagao</a></p>
		</div>
		<div id="ColRight">
			<h2>Bạn vẫn còn các thắc mắc?</h2>
			<p>Không ai rõ sản phẩm NAGAO hơn chúng tôi, và chúng tôi ở đây để giúp đỡ, hướng dẫn. Vui lòng kiểm tra phần FAQs nơi các bạn có thể tìm thấy câu trả lời cho thắc mắc của mình. Nếu bạn vẫn không thể tìm thấy câu trả lời thỏa đáng, các bạn có thể <a href="javascript:ShowContact()"> liên lạc trực tiếp với Linh Chi NAGAO.</a></p>
			<form name="Contact" method="post" onsubmit="return SendContact()" />
			<div id="ContactContainer">
				<div class="LabelFCol">Họ tên <span>*</span> </div><div class="LabelSCol">Email <span>*</span></div>
				<div class="LabelFCol"><input type="text" name="name" maxlength="25" required /></div><div class="LabelSCol"><input type="email"  name="emailcontact"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required /></div>
				<div class="LabelFCol">Phone <span>*</span> </div>
				<div class="LabelFCol"><input type="text" name="phone" pattern="[0-9].{7,}" required /></div>
				<div class="LabelFCol">Lời nhắn <span>*</span> </div>
				<div><textarea name="comment" required> </textarea> </div>
				<div><input type="submit" name="send" value="Gởi Tin"/> </div>
				<div> 
					<p id="ContactNotice"></p>					
				</div>
			</div>
			</form>
	    </div>
	</div>

	<!--Kết thúc phần thân-->
<?php include "php/footer.php"; ?>



</body>
</html>
