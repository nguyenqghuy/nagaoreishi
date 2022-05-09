<form name="RegisterEmail" method="post" onsubmit="return Status()">
	<div id="NewsLetterFrame" class="col11"> 
		<div id="NewsIntro">
				<div> 
					Kết nối với NAGAO
				</div>
				<ul>
					<li>Nhận thư sự kiện</li>
					<li>Ưu đãi giá</li>
				</ul>
				<input name="Email" type="email" value="Nhập Email của bạn ..."  onfocus="Clear()" onblur="Fill()" />
				<label id="ThanksJoin">Cảm ơn đã gia nhập</label>
				<label id="ErrorNotice"></label>
		</div>
		<div id="BtnJoinUs"> <input type="submit" name="JoinUs" value="Gia nhập" /> </div>
	</div>
</form>	
	<div id="SimpleMenu" class="col11">
		<ul>
			<li><a href="http://www.namlinhchinagao.com" title="nam linh chi nagao ngan ngua ung thu" id="trang-chu-foot">Trang chủ</a></li>
			<li><a href="/faqs/" title="cau hoi thuong gap ve mua, su dung nam linh chi" id="faq-foot">Câu hỏi thường gặp</a></li>
			<li><a href="/chinh-sach-ban-hang/" title="Chinh sach ban hang cua nam linh chi NAGAO" id="chinh-sach-ban-foot">Chính sách bán hàng</a></li>
			<li><a href="/chung-toi/" title="Thong tin ve co so trong nam linh chi NAGAO" id="chung-toi-foot">Về Chúng Tôi</a></li>
			<li><a href="/cua-hang-linh-chi/" title="Linh Chi Sản phẩm sức khỏe">Cửa hàng</a></li>
			<li><a href="/ho-tro/" title="Ban can ho tro khi dat mua nam linh chi?" id="ho-tro-foot">Hỗ trợ</a></li>
			<li><a href="/dai-ly/" title="Danh sach dai ly nha phan phoi" id="dai-ly-foot">Đại lý</a></li>
		<!--	<li><a href="/partner/" title="Trao doi link voi cac partner" id="partner-foot">Link Exchange</a></li>-->
			<li><a href="/sitemap.html" title="So do website cua nam linh chi NAGAO" id="sitemap-foot">Site Map</a></li>
			<li><a href="/lien-ket/" title="Website liên kết" id="lien-ket-foot">Website liên kết</a></li>
		</ul>
	 </div>
	 <div id="Footer">
	 	Website được phát triển, thương mại số bởi NAGAO
	 </div>
<?php 

if(strpos($_SERVER["SERVER_NAME"], "namlinhchinagao.com") === false){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/nagaoreishi.php'); 
}else {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/namlinhchinagao.php'); 
	
}	
?>
