
$(document).ready(function(){
	$("#MenuButton").click(function(){
		$("#SmallMenu").slideToggle();
	});
});



//Sử dụng cho google analytics

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70495518-1', 'auto');
  ga('require', 'linkid');
  ga('send', 'pageview');


// Sử dụng cho facebook

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=878485798882330";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// sử dụng cho input điền email
		function Clear(){
			if(document.forms["RegisterEmail"]["Email"].value == "Nhập Email của bạn ...") {
				document.forms["RegisterEmail"]["Email"].value = "";
			}
		}
		function Fill(){
			if(document.forms["RegisterEmail"]["Email"].value == "") {
				document.forms["RegisterEmail"]["Email"].value = "Nhập Email của bạn ...";
			}
		}

function Status(){
		
		if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.forms["RegisterEmail"]["JoinUs"].value = "Xin chờ ...";
				document.forms["RegisterEmail"]["JoinUs"].disabled = true;
                document.getElementById("ErrorNotice").innerHTML = xmlhttp.responseText;
				document.forms["RegisterEmail"]["JoinUs"].style.display = 'none';
				document.forms["RegisterEmail"]["Email"].style.display= 'none';
				document.getElementById("NewsIntro").style.width = "100%";
				document.getElementById("ThanksJoin").style.display = "inline";
				
            }
        }
        xmlhttp.open("GET","/php/RegEmail.php?email="+document.forms["RegisterEmail"]["Email"].value,true);
        xmlhttp.send();
		return false;
}
