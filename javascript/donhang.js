// JavaScript Document
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
};

/*1234..format();           // "1,234"
12345..format(2);         // "12,345.00"
123456.7.format(3, 2);    // "12,34,56.700"
123456.789.format(2, 4);  // "12,3456.79"
*/
//Cập nhật bảng giá phần thông tin số lượng và Giá tiền từng món hàng
function UpdateOrder(x) {
	var LabelQuantity = "Quantity" + x;
	var LabelAmount = "Amount" + x;
	var LabelPrice = "Price" + x;
	var EQuantity = document.getElementById(LabelQuantity);
	var EAmount = document.getElementById(LabelAmount);
	var EPrice = document.getElementById(LabelPrice);
	var Price = EPrice.innerHTML;
/*	var s = "This., -/ is #! an $ % ^ & * example ;: {} of a = -_ string with `~)() punctuation";
	var punctuationless = s.replace(/[.,-\/#!$%\^&\*;:{}=\-_`~()]/g,"");
	var finalString = punctuationless.replace(/\s{2,}/g," ");*/
	if(document.forms["Order"][x].value=="0"){
		EQuantity.innerHTML = "-";
		EAmount.innerHTML = "-";
	}else{
		EQuantity.innerHTML = document.forms["Order"][x].value;
		Price = Price.replace(/[.]/g, "");
		EAmount.innerHTML = (parseInt(Price) * parseInt(EQuantity.innerHTML)).format();
	}
	
	CalSubTotalPrice();
}

//Tính giá trị SubTotal và cập nhật các giá trị discount
function CalSubTotalPrice(){
	var XL = document.getElementById("AmountXL").innerHTML;
	var NT = document.getElementById("AmountNT").innerHTML;
	var CC = document.getElementById("AmountCC").innerHTML;
	var CK = document.getElementById("AmountCK").innerHTML;
//	var TP = document.getElementById("AmountTP").innerHTML;
	
	if(XL=="-"){ XL = 0;} else { XL = XL.replace(/[.]/g, "");}
	if(NT=="-"){ NT = 0;} else { NT = NT.replace(/[.]/g, "");}
	if(CC=="-"){ CC = 0;} else { CC = CC.replace(/[.]/g, "");}
	if(CK=="-"){ CK = 0;} else { CK = CK.replace(/[.]/g, "");}
//	if(TP=="-"){ TP = 0;} else { TP = TP.replace(/[.]/g, "");}
	
//	var SubTotal = parseInt(XL) + parseInt(NT) + parseInt(CC) + parseInt(CK) + parseInt(TP);
	var SubTotal = parseInt(XL) + parseInt(NT) + parseInt(CC) + parseInt(CK);
	if(SubTotal == 0){// nếu Giá SubTotal bằng 0
		document.getElementById("SubTotal").innerHTML = "-";
		document.getElementById("DiscountConnect").innerHTML = "-";
		document.getElementById("DiscountPayment").innerHTML = "-";
		document.getElementById("DiscountValue").innerHTML = "-";
		document.getElementById("DiscountCoupon").innerHTML = "-";
		
	}else{
		//hiển thị Subtotal
		document.getElementById("SubTotal").innerHTML = (SubTotal).format();	
		//Điều chỉnh trạng thái yêu cầu email
		if(document.forms["Order"]["Connect"].checked){	document.forms["Order"]["Email"].required = true;}
		else{ document.forms["Order"]["Email"].required = false;}
		//Có Coupon Code
		if(document.forms["Order"]["DiscountCouponHidden"].value != ""){
			var DiscountCoupon = document.forms["Order"]["DiscountCouponHidden"].value;
			document.getElementById("CouponRow").style.display = "block";
			document.getElementById("LabelCoupon").innerHTML = "Giảm giá Coupon(" + DiscountCoupon + "%)";
			//Lúc discount Coupon sẽ hủy bỏ các dạng discount khác
			document.getElementById("DiscountConnect").innerHTML = "-";
			document.getElementById("DiscountPayment").innerHTML = "-";
			document.getElementById("DiscountValue").innerHTML = "-";
			//tính discount coupon
			document.getElementById("DiscountCoupon").innerHTML = "-" + (parseInt(SubTotal) * parseInt(DiscountCoupon)/100).format();
			document.forms["Order"]["Email"].required = false; //Tắt yêu cầu email khi đã có mã coupon code

		}else{
			//Không có coupon Code
			
				/*Tính lại DiscountConnect*/
			if(document.forms["Order"]["Connect"].checked){
				document.getElementById("DiscountConnect").innerHTML = "-" + (parseInt(SubTotal) * 0.01).format();
			}
			/*Tính lại DiscountPayment*/
			if(document.forms["Order"]["Payment"].value != "direct"){
				document.getElementById("DiscountPayment").innerHTML = "-" + (parseInt(SubTotal) * 0.03).format();
			}else{
				document.getElementById("DiscountPayment").innerHTML = "-";
				
			}
			/*Tinh lai khoản DiscountValue*/
			if(SubTotal >= 3000000){
				var DiscountValue = parseInt(SubTotal * 0.08);
				document.getElementById("DiscountValue").innerHTML = "-" + DiscountValue.format();
			}else{
				document.getElementById("DiscountValue").innerHTML = "-";
			}
		}
	}
	CalTotalPrice();
	
}

/*function UpdateDiscountConnect(){	
	var SubTotal = document.getElementById("SubTotal").innerHTML;
	if(document.forms["Order"]["Connect"].checked){
		document.forms["Order"]["Email"].required = true;
		if(SubTotal == "-"){
			document.getElementById("DiscountConnect").innerHTML = "-";
		}else{
			SubTotal = SubTotal.replace(/[.]/g,"");
			document.getElementById("DiscountConnect").innerHTML = "-" + (parseInt(SubTotal) * 0.01).format();
			CalTotalPrice();
		}		
	}else{
		document.getElementById("DiscountConnect").innerHTML = "-";
		CalTotalPrice();
		document.forms["Order"]["Email"].required = false;
	}	
}


function UpdateDiscountPayment(){
	var SubTotal = document.getElementById("SubTotal").innerHTML;
	if(document.forms["Order"]["Payment"].value == "direct"){
		document.getElementById("DiscountPayment").innerHTML = "-";
		CalTotalPrice();
	}else{
		if(SubTotal == "-"){
			document.getElementById("DiscountPayment").innerHTML = "-";
		}else{
			SubTotal = SubTotal.replace(/[.]/g,"");
			document.getElementById("DiscountPayment").innerHTML = "-" + (parseInt(SubTotal) * 0.03).format();
			CalTotalPrice();
		}		
	
	}

}
*/
function CalTotalPrice(){
	var SubTotal = document.getElementById("SubTotal").innerHTML;
	var DiscountConnect = document.getElementById("DiscountConnect").innerHTML;
	var DiscountPayment = document.getElementById("DiscountPayment").innerHTML;
	var DiscountValue = document.getElementById("DiscountValue").innerHTML;
	var DiscountCoupon = document.getElementById("DiscountCoupon").innerHTML;
	if(SubTotal == "-"){
		document.getElementById("Total").innerHTML = "-";
	}else{
		SubTotal = SubTotal.replace(/[.]/g,"");
		if(DiscountConnect == "-"){ DiscountConnect = 0;}else { DiscountConnect = DiscountConnect.replace(/[.]/g,"");}	
		if(DiscountPayment == "-"){ DiscountPayment = 0;}else { DiscountPayment = DiscountPayment.replace(/[.]/g,"");}
		if(DiscountValue == "-"){ DiscountValue = 0;}else { DiscountValue = DiscountValue.replace(/[.]/g,"");}
		if(DiscountCoupon == "-"){ DiscountCoupon = 0;}else { DiscountCoupon = DiscountCoupon.replace(/[.]/g,"");}
		document.getElementById("Total").innerHTML = (parseInt(SubTotal) + parseInt(DiscountConnect) + parseInt(DiscountPayment) + parseInt(DiscountValue) + parseInt(DiscountCoupon)).format();
	}
}

function SetHidden(){
	if(document.getElementById("Total").innerHTML != "-"){
			document.forms["Order"]["TotalHidden"].value = document.getElementById("Total").innerHTML;
			document.forms["Order"]["SubTotalHidden"].value = document.getElementById("SubTotal").innerHTML;
			document.forms["Order"]["DiscountConnectHidden"].value = document.getElementById("DiscountConnect").innerHTML;
			document.forms["Order"]["DiscountPaymentHidden"].value = document.getElementById("DiscountPayment").innerHTML;
			document.forms["Order"]["DiscountValueHidden"].value = document.getElementById("DiscountValue").innerHTML;
			document.forms["Order"]["DiscountCouponValueHidden"].value = document.getElementById("DiscountCoupon").innerHTML;
			document.forms["Order"]["PriceXL"].value = document.getElementById("PriceXL").innerHTML;
			document.forms["Order"]["PriceNT"].value = document.getElementById("PriceNT").innerHTML;
			document.forms["Order"]["PriceCC"].value = document.getElementById("PriceCC").innerHTML;
			document.forms["Order"]["PriceCK"].value = document.getElementById("PriceCK").innerHTML;
//			document.forms["Order"]["PriceTP"].value = document.getElementById("PriceTP").innerHTML;
			document.forms["Order"]["OrderNow"].disabled = true;
			return true;
	}
	return false;
}


function CheckCode(){
	var pattern = new RegExp("[A-Z0-9]+");
	if(document.forms["Order"]["couponcode"].value != ""){	
		if(pattern.test(document.forms["Order"]["couponcode"].value) && document.forms["Order"]["couponcode"].value.length == 10){
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var ArrResult = xmlhttp.responseText.split("-");
					if(ArrResult[0]=="1"){// mã coupon được xác thực thành công
						document.forms["Order"]["CouponCodeHidden"].value = document.forms["Order"]["couponcode"].value;
						document.forms["Order"]["DiscountCouponHidden"].value = ArrResult[2];
						document.forms["Order"]["couponcode"].disabled = true;
						CalSubTotalPrice();
					}else{ //mã coupon sai
						document.forms["Order"]["couponcode"].value = "";
					}
					document.getElementById("CouponMsg").innerHTML = ArrResult[1];
					document.getElementById("CouponMsg").style.display = 'block';
					
					
				}
			}
			
			var couponcode = document.forms["Order"]["couponcode"].value;
			var url = "couponcode=" + couponcode;
			xmlhttp.open("POST","/php/CheckCoupon.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send(url);
			
		}
		else{
			document.getElementById("CouponMsg").innerHTML = "Sai mã coupon";
			document.forms["Order"]["couponcode"].value = "";
		}
	}
}