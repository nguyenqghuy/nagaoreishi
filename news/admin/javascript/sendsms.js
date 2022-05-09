$(document).ready(function(){
	$("a.EditPhone").click(function(){
		$(this).next().slideToggle();							
	});


});

function SendSMS(){
 	var PhoneList = document.forms["admin"]["PhoneList"];
	var MessageForm = document.forms["admin"]["MessageForm"];
	var Variable = document.forms["admin"]["Variable"];
	var SendButton = document.forms["admin"]["Send"];
	var StrDisplay = "";
	if(PhoneList.value != "" && MessageForm.value!=""){	
			
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			SendButton.value = "Wait....";
			SendButton.disabled = true;
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var ArrResult = xmlhttp.responseText.split("*");
					if(ArrResult[0]=="1"){
						StrDisplay =  "Success: " + ArrResult[1] + ArrResult[2];
						document.getElementById("Msg02").innerHTML = ArrResult[3];
				
						
					}else if(ArrResult[0]=="0") {
						StrDisplay =  "Fail: " +  ArrResult[1];
						document.getElementById("Msg02").innerHTML = ArrResult[2];
					}else if(ArrResult[0]=="2"){
						StrDisplay =  "Some Error: " +  ArrResult[1] + ArrResult[2];
						document.getElementById("Msg02").innerHTML = ArrResult[3];
					}
					else{
						StrDisplay = xmlhttp.responseText;
					}
					document.getElementById("Msg01").innerHTML = StrDisplay;
					SendButton.value = "Send";
					SendButton.disabled = false;
				}
				
				
				
			}
			
			var url = "PhoneList=" + PhoneList.value + "&MessageForm=" + MessageForm.value + "&Variable=" + Variable.value;
			xmlhttp.open("POST","SendSMSNow.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send(url);
			
	
	}else{
		document.getElementById("Msg").innerHTML = "Not enought information";
	}
	
}