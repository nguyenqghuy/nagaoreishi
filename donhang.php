<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Don hang nam Linh Chi NAGAO</title>
<link rel="alternate" href="http://www.namlinhchinagao.com/don-hang/" hreflang="vi-vn" />
<meta name="description" content="Dat mua nam Linh Chi NAGAO de bao ve suc khoe gia dinh ban" />
<link rel="canonical" href="http://www.namlinhchinagao.com/don-hang/" />
<meta http-equiv="content-language" content="vi"/>
<meta name="robots" content="INDEX, FOLLOW" />
<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/donhang.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/common.js" type="text/javascript"> </script>
<script src="/javascript/donhang.js" type="text/javascript"> </script>
</head>

<body>
<?php include "php/header.php"; ?>
	
	<!--Phần thân-->
		<?php require "php/constant.php"; ?>

	<div id="Breadcum" class="col11">
		<a href="http://www.namlinhchinagao.com" title="Về lại trang chủ">Trang chủ</a> ► <a href="/cua-hang-linh-chi/" title="Linh Chi Sản phẩm sức khỏe">Cửa hàng Linh Chi</a>  ► <a>Đơn Hàng</a>
	</div>
	<h1 id="TitlePage" class="col11">
		ĐƠN ĐẶT HÀNG LINH CHI
	</h1>
	<form name="Order" method="post" enctype="multipart/form-data" action="/xu-ly-don-hang/" onsubmit="return SetHidden();">
	<div id="BillContainer" class="col11">
		<div>
			<nav><span>1.</span>Liên Lạc</nav>
			<btr><span>*</span> Tên</btr>
			<btr><input type="text" name="firstname" maxlength="25" required tabindex="0" autofocus /></btr>
			<btr><span>*</span> Họ</btr>
			<btr><input type="text" name="lastname"  maxlength="15" required /></btr>
			<btr><span>*</span> Điện thoại</btr>
			<btr><input type="text" name="phone" pattern="[0-9].{7,}" required /></btr>
			<btr><span>*</span> Địa chỉ</btr>
			<btr><input type="text" name="address" pattern=".{10,}" maxlength="50" required /></btr>
			<btr><span>*</span> Thành phố</btr>
			<btr><input type="text" name="city" pattern=".{2,}" maxlength="20" required /></btr>
			<btr title="Nhập email và chọn kết nối cùng NAGAO để nhận giảm giá 1%">Email(không bắt buộc)</btr>
			<btr><input type="email"  name="Email"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Nhập email và chọn kết nối cùng NAGAO để nhận giảm giá 1%" /></btr>
			<btr><label title="Giảm ngay 1% giá trị đơn hàng">Kết nối cùng NAGAO</label> <input type="checkbox" name="Connect" value="connect" onchange="CalSubTotalPrice()" /></btr>
			
		</div>
		<div>
			<nav><span>2.</span>Đặt Mua</nav>
			<btr><i>Với các yêu cầu đặt hàng nhỏ hơn 1 kg, các bạn vui lòng liên lạc số điện thoại 0901 466 842 để được hướng dẫn.</i></btr>
			<btr>Linh chi xắt lát</btr>
			<btr><select name="XL" onchange="UpdateOrder('XL');" >
					<option value="0">Chọn khối lượng?</option>
					<option value="1">1 kg</option>
					<option value="2">2 kg</option>
					<option value="3">3 kg</option>
					<option value="4">4 kg</option>
					<option value="5">5 kg</option>
					<option value="6">6 kg</option>
					<option value="7">7 kg</option>
					<option value="8">8 kg</option>
					<option value="9">9 kg</option>
					<option value="10">10 kg</option>
				</select>
			</btr>
			<btr>Linh chi nguyên tai</btr>
			<btr><select name="NT" onchange="UpdateOrder('NT');">
					<option value="0">Chọn khối lượng?</option>
					<option value="1">1 kg</option>
					<option value="2">2 kg</option>
					<option value="3">3 kg</option>
					<option value="4">4 kg</option>
					<option value="5">5 kg</option>
					<option value="6">6 kg</option>
					<option value="7">7 kg</option>
					<option value="8">8 kg</option>
					<option value="9">9 kg</option>
					<option value="10">10 kg</option>
				</select>
			</btr>
			<btr>Linh chi cao cấp</btr>
			<btr><select name="CC" onchange="UpdateOrder('CC');">
					<option value="0">Chọn số lượng?</option>
					<option value="1">1 hộp</option>
					<option value="2">2 hộp</option>
					<option value="3">3 hộp</option>
					<option value="4">4 hộp</option>
					<option value="5">5 hộp</option>
					<option value="6">6 hộp</option>
					<option value="7">7 hộp</option>
					<option value="8">8 hộp</option>
					<option value="9">9 hộp</option>
					<option value="10">10 hộp</option>
				</select>
			</btr>
			<btr>Linh chi cao cấp không hộp</btr>
			<btr><select name="CK" onchange="UpdateOrder('CK');">
					<option value="0">Chọn số lượng?</option>
					<option value="1">1 kg</option>
					<option value="2">2 kg</option>
					<option value="3">3 kg</option>
					<option value="4">4 kg</option>
					<option value="5">5 kg</option>
					<option value="6">6 kg</option>
					<option value="7">7 kg</option>
					<option value="8">8 kg</option>
					<option value="9">9 kg</option>
					<option value="10">10 kg</option>
				</select>
			</btr>
<!--			
			<btr>Linh chi tróc phấn</btr>
			<btr><select name="TP" onchange="UpdateOrder('TP');">
					<option value="0">Chọn số lượng?</option>
					<option value="1">1 kg</option>
					<option value="2">2 kg</option>
					<option value="3">3 kg</option>
					<option value="4">4 kg</option>
					<option value="5">5 kg</option>
					<option value="6">6 kg</option>
					<option value="7">7 kg</option>
					<option value="8">8 kg</option>
					<option value="9">9 kg</option>
					<option value="10">10 kg</option>
				</select>
			</btr>
-->
			<btr>Mã Coupon giảm giá</btr>
			<btr><input type="text" name="couponcode" id="couponcode" value="" maxlength="10" pattern="[A-Z0-9]+"/>
				<input type="button" name="CheckCoupon" value="Xác thực" onclick="CheckCode();" />
			<div id="CouponMsg"></div>
			</btr>
			<nav><span>3.</span>Thanh toán</nav>
			<btr><input type="radio" name="Payment" value="<?php echo DIRECTPAYMENT; ?>" onchange="CalSubTotalPrice()" /> 
					<label title="Thanh toán tiền mặt ngay khi nhận hàng">Trực tiếp</label></btr>
			<btr><input type="radio" name="Payment" value="<?php echo BANKPAYMENT; ?>" checked="checked" onchange="CalSubTotalPrice()"/> 
					<label title="Nhận giảm giá ngay 3% cho quý khách chọn thanh toán qua ngân hàng">Chuyển tiền vào ngân hàng</label>
			</btr>
			<btr><label id="BankInfo">Ngân hàng Vietcombank - CN Tân Bình<br />
				Số tài khoản: 0441000699334<br />
				Chủ TK: CO SO SX & MUA BAN NAM CAC LOAI NAGAO
				</label>
			</btr>
		</div>
		<div>
			<nav><span>4.</span>Tóm lược</nav>
			<div id="OrderReview">
				<div class="OrderRow">
					<label>Tên</label>
					<label>Giá</label>
					<label>Sl</label>
					<label>Tiền</label>
				</div> 
				<div class="OrderRow">
					<label title="Linh Chi xắt lát">LCXL</label>
					<label id="PriceXL">1.850.000</label>
					<label id="QuantityXL">-</label>
					<label id="AmountXL">-</label>
				</div>
				<div class="OrderRow">
					<label title="Linh Chi nguyên tai">LCNT</label>
					<label id="PriceNT">1.850.000</label>
					<label id="QuantityNT">-</label>
					<label id="AmountNT">-</label>
				</div>
				<div class="OrderRow">
					<label title="Linh Chi cao cấp">LCCC</label>
					<label id="PriceCC">950.000</label>
					<label id="QuantityCC">-</label>
					<label id="AmountCC">-</label>
				</div>
				<div class="OrderRow">
					<label title="Linh Chi cao cấp không hộp">LCCCKH</label>
					<label id="PriceCK">2.960.000</label>
					<label id="QuantityCK">-</label>
					<label id="AmountCK">-</label>
				</div>
<!--
				<div class="OrderRow">
					<label title="Linh Chi tróc phấn">LCTrPhan</label>
					<label id="PriceTP">800.000</label>
					<label id="QuantityTP">-</label>
					<label id="AmountTP">-</label>
				</div>
-->
				<div class="CalRow">
					<label>Thành tiền</label>
					<label id="SubTotal">-</label>
				</div>
				<div class="CalRow">
					<label>Kết nối NAGAO</label>
					<label id="DiscountConnect">-</label>
				</div>
				<div class="CalRow">
					<label title="Thanh toán qua ngân hàng">Thanh toán NH</label>
					<label id="DiscountPayment">-</label>
				</div>
				<div class="CalRow">
					<label title="Giảm giá cho đơn hàng >=2.500.000 VND">Đơn hàng lớn</label>
					<label id="DiscountValue">-</label>
				</div>
				
				<div class="CalRow" id="CouponRow">
					<label title="Giảm giá Coupon" id="LabelCoupon">Giảm giá Coupon</label>
					<label id="DiscountCoupon">-</label>
				</div>

				<div class="CalTotal">
					<label>Tổng cộng</label>
					<label id="Total">-</label>
				</div>
			</div>
			<label id="CommentLabel">Ghi chú</label>
			<btr><i>1 kg nấm linh chi có 10 túi nấm nhỏ, bạn có thể đặt 1 kg nấm xắt lát có 6 túi xắt lát và 4 túi nguyên tai bằng cách ghi chú yêu cầu dưới đây.</i></btr>
			<btr><textarea name="comment" > </textarea></btr>
			<btr>Khi đăng ký đơn hàng, bạn xác nhận rằng, bạn đã hiểu và chấp thuận các <a href="/chinh-sach-ban-hang/" target="_blank" id="chinh-sach-ban-hang01"> điều khoản mua bán</a></btr>
			<btr><input type="submit" name="OrderNow" value="Đặt hàng" />
			<input type="hidden" name="TotalHidden"/>
			<input type="hidden" name="SubTotalHidden"/>
			<input type="hidden" name="DiscountConnectHidden" />
			<input type="hidden" name="DiscountPaymentHidden" />
			<input type="hidden" name="DiscountValueHidden" />
			<input type="hidden" name="PriceXL"/>
			<input type="hidden" name="PriceNT"/>
			<input type="hidden" name="PriceCC"/>
			<input type="hidden" name="PriceCK"/>
			<input type="hidden" name="PriceTP"/>
			<input type="hidden" name="CouponCodeHidden"/> <!--Lưu mã coupon-->
			<input type="hidden" name="DiscountCouponValueHidden"/><!--lưu giá tiền được giảm-->
			<input type="hidden" name="DiscountCouponHidden"/><!--Lưu số phần trăm được giảm-->
			
			</btr>
		</div>
	</div>
	</form>
	<!--Kết thúc phần thân-->
	 <div id="Footer">
	 	Website được phát triển, thương mại số bởi NAGAO
	 </div>

</body>
</html>
