<?php 
date_default_timezone_set("Asia/Ho_Chi_Minh");
/*Define for database*/
define("DATASERVER", "nguyenqghuy.domaincommysql.com");
define("USER", "nagaoacc");
define("PASSWORD","huyquang");
define("DATABASE","nagao");
/*Define for Order Status*/
define("WAIT_FOR_PROCEEDING",0);
define("WAIT_FOR_MONEY",1);
define("RECEIVED_MONEY",2);
define("TRANSFERED", 3);
define("NOT_ORDER", 4);
$StatusOrder = array("Wait for Proccessing", "Wait for Money", "Received Money", "Transfered", "Not Order");
$OrderSMS = array( 1 => "Cam on quy khach da dat hang tai NAGAO, xin dien MA DON HANG --0-- o noi dung chuyen tien, hang se duoc chuyen ngay sau khi tai khoan bao co",
					2 => "NAGAO da nhan tien tu MA DON HANG --0-- . Cam on quy khach ",
					3 => "Linh chi cua MA DON HANG --0-- da duoc chuyen"
);
/*Define for method payment*/
define("BANKPAYMENT", "bank");
define("DIRECTPAYMENT", "direct");
/*Define for mail*/
define("MAILSERVER","smtp.domain.com");
define("MAILSECURE","ssl");
define("MAILPORT",465);
define("MAILAUTH",true);
define("MAILUSER","info@namlinhchinagao.com");
define("MAILPASS","Nagao2014");
/*Define for coupon status*/
define("ACTIVE",1);
define("INACTIVE",0);

/*Define for SMS */
define("USERSMS","nagao001");
//define("PASSSMS","9dc13ef8ca35fa0497eadc37f9be679fb2acf405");djsoqlj
define("PASSSMS","HfvJNrA1oN6354AGhOZ1AvFrRJ8=");
define("URLLOGIN","http://mkt.vivas.vn:9080/SMSBNAPI/login");
define("URLSENDSMS","http://mkt.vivas.vn:9080/SMSBNAPI/send_sms");
define("URLLOGOUT","http://mkt.vivas.vn:9080/SMSBNAPI/logout");
define("BRANDNAME","NAGAO");
define("TYPESMS",1);
define("SHAREKEY","489375");
$ArrLoginStatus = array(0 => "Đăng nhập thành công",
					1 => "Sai User Name",
					2 => "Sai Password",
					21 => "Quá số lượng Request đến hệ thống",
					50 => "Lỗi xử lý",
					51 => "Lỗi xử lý",
					52 => "Lỗi xử lý",
					98 => "Lỗi Sai Protocol gọi Request",
					99 => "Lỗi thiếu tham số gọi Request"
);

$ArrSendSMSStatus = array(0 => "Request được tiếp nhận thành công",
						3 => "Request bị từ chối vì BrandName không tồn tại",
						4 => "Request bị từ chối vì không đúng template",
						5 => "Request bị từ chối vì Checksum sai",
						6 => "Request bị từ chối vì trùng ID",
						8 => "Request bị từ chối vì vượt hạn mức gửi tin",
						9 => "Request bị từ chối vì thiếu loại tin",
						10 => "Request bị từ chối vì thiếu thời gian gởi",
						12 => "Request bị từ chối vì trùng MSGID",
						13 => "Request bị từ chối vì vượt quá số điện thoại trong request",
						14 => "Request bị từ chối vì số điện thoại sai",
						20 => "Request bị từ chối vì chưa đăng nhập hoặc mất session",
						21 => "Request bị từ chối vì quá số lượng request đến hệ thống",
						50 => "Lỗi xử lý",
						51 => "Lỗi xử lý",
						52 => "Lỗi xử lý",
						98 => "Sai protocol",
						99 => "Lỗi thiếu tham số gọi Request"
);

$ArrLogoutStatus = array(0 => "Đăng xuất thành công",
						20 => "Request bị từ chối vì chưa đăng nhập hoặc mất session",
						21 => "Request bị từ chối vì quá số lượng request đến hệ thống",
						50 => "Lỗi xử lý",
						51 => "Lỗi xử lý",
						52 => "Lỗi xử lý",
						98 => "Sai protocol",
						99 => "Lỗi thiếu tham số gọi Request"
);

?>