// JavaScript Document
$(document).ready(function(){
	$("a.buttonFAQ").click(function(){
		$(this).addClass("activetag");
		$(this).siblings().removeClass("activetag");// bật tắt nút màu sáng
		var category = $(this).data("filter");
		if(category =="*"){
			$("#Topics ~ div.col10").fadeIn(500); // Hiện lên hết
		}else{
			$("#Topics ~ div.col10").fadeOut(500);// Tắt hết
			
			var strfilter = "[data-cat='" + category + "']";
			$("#Topics ~ div.col10").filter(strfilter).fadeIn(500);
		}
	});
	
	//Tắt mở cửa sổ nội dung trả lời
	$("h2").click(function(){
		if($(this).next().css("display") == "none"){
			$(this).css("background-color","#00aeff");		
			$(this).children("span").slideDown(); //Hiện dấu X lên
			$(this).next().slideDown();//bật cửa sổ nội dung trả lời
			
			//tắt cửa sổ của những nội dung trả lời khác
			$(this).parent().siblings("div.col10").children("h2").css("background-color","#191919"); // tắt màu
			$(this).parent().siblings("div.col10").find("span").hide(); // tắt nút SPAN
			$(this).parent().siblings("div.col10").children("div").hide(); // tắt thẻ div nội dung
		}else{
			$(this).css("background-color","#191919");		
			$(this).children("span").slideUp();
			$(this).next().slideUp();; 
		}
	});
	
});