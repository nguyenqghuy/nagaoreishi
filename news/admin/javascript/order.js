$(document).ready(function(){
	$("a.EditPhone").click(function(){
		$(this).next().slideToggle();							
	});


});

function EditPhone(Id){
	var PhoneElementName = "EditPhone_" + Id;
	var PhoneLabelName = "Phone_" + Id;
	var Phone = document.getElementById(PhoneElementName);
	var pattern = new RegExp("[0-9]*");
	if(Phone.value != ""){	
		if(pattern.test(Phone.value) && Phone.value.length >= 10){
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
					if(ArrResult[0]=="1"){
						document.getElementById(PhoneLabelName).innerHTML = Phone.value;
						Phone.value = "Success";
						
					}else{
						Phone.value = "Fail";
					}
					
					
				}
			}
			
			var url = "Phone=" + Phone.value + "&Id=" + Id;
			xmlhttp.open("POST","ChangePhone.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send(url);
			
		}
		else{
			Phone.value = "Sai số ĐT";
		}
	}
	
}

//Change Status of Order
function ChangeStatus(sel){
	if(sel.selectedIndex == 0){
		return false;	
	}
	c = confirm(' OK? ');
	if(c){
		document.forms["admin"]["NameStatus"].value = sel.name;
		var Id = sel.name.split("_")[1];
		var PhoneNameLabel = "Phone_" + Id;
		var OrderIdLabel = "OrderId_" + Id;
		document.forms["admin"]["SendPhone"].value = document.getElementById(PhoneNameLabel).innerHTML;
		document.forms["admin"]["OrderId"].value = document.getElementById(OrderIdLabel).innerHTML;
		sel.form.submit();
	}else{
		sel.selectedIndex = 0;	
	}
	
}
