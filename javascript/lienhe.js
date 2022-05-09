// JavaScript Document
function ShowContact(){
	document.getElementById("ContactContainer").style.display = "block";
}

function SendContact(){
		document.forms["Contact"]["send"].value = "Xin chờ ...";
		document.forms["Contact"]["send"].disabled = true;
		if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
/*                document.getElementById("ContactNotice").style.display = "block";
*/				document.getElementById("ContactNotice").innerHTML = xmlhttp.responseText;
				document.forms["Contact"]["send"].style.display = 'none';
				
				
            }
        }
		var name = document.forms["Contact"]["name"].value;
		var email = document.forms["Contact"]["emailcontact"].value;
		var phone = document.forms["Contact"]["phone"].value;
		var comment = document.forms["Contact"]["comment"].value;
		var url = "name=" + name + "&phone=" + phone + "&comment=" + comment + "&email=" + email;
		xmlhttp.open("POST","/php/GetContact.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(url);
		
		
		return false;
}