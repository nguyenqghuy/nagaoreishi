// JavaScript Document
$(document).ready(function(){
	$("a.buttonFAQ").click(function(){
		$(this).addClass("activetag");
		$(this).siblings().removeClass("activetag");// bật tắt nút màu sáng
		var category = $(this).data("filter");
		$("#BriefGuide > div").hide(500); // tắt hết
		var strfilter = "[data-cat='" + category + "']";
		$("#BriefGuide > div").filter(strfilter).show(500);
	});
	
	
});