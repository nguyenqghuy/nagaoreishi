<?php header('Content-type: text/html; charset=ISO-8859-1');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Tin tuc - News - NAGAO</title>
<link rel="alternate" type="application/rss+xml" title="Feed RSS News" href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/rss.php'; ?>" /> 
<meta name="description" content="Tin tuc su kien cua linh chi nagao, san pham giu suc khoe, lam dep" />
<link rel="canonical" href="http://www.namlinhchinagao.com/ChungToi.php" />

<meta property="og:locale" content="vi_VN" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Tin tuc - News - NAGAO" />
<meta property="og:description" content="Tin tuc su kien cua linh chi nagao, san pham giu suc khoe, lam dep." />
<meta property="og:url" content="http://www.namlinhchinagao.com/news/archivio.php" />
<meta property="og:site_name" content="Linh Chi NAGAO" />

<meta property="og:image" content="http://www.namlinhchinagao.com/picture/linhchi-nagao.jpg" />


<link rel="icon" href="http://www.namlinhchinagao.com/picture/linhchiNAGAOICON.jpg" />
<link rel="shortcut icon" href="http://www.namlinhchinagao.com/picture/linhchiNAGAOICON.jpg" />

<meta name="robots" content="index,follow" />

<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/archivio.css" />
<script src="../javascript/common.js"> </script>
</head>

<body>
<?php 
$testo_cerca = array(
            '{\[email\](\r\n|\r|\n)*([a-zA-Z0-9\._-]+@(([a-zA-Z0-9_-])+\.)+[a-z]{2,4})\[/email\]}siU',
            '{\[email=(\w[\w\-\.\+]*?@\w[\w\-\.]*?\w\.[a-zA-Z]{2,4})\](.+)?\[\/email\]}siU',
            '{(\[)(url)(])((http|ftp|https)://)([^;<>\*\(\)"\s]*)(\[/url\])}siU',
            '{(\[)(url)(=)([\'"]?)((http|ftp|https)://)([^;<>\*\(\)"\s]*)(\\4])(.*)(\[/url\])}siU',
            '{(\[)(callto)(])((callto):)([^;<>\*\(\)"\s]*)(\[/callto\])}siU',
            '{(\[)(callto)(=)([\'"]?)((callto):)([^;<>\*\(\)"\s]*)(\\4])(.*)(\[/callto\])}siU',
            '{(\[)(size)(=)([\'"]?)([0-9]*)(\\4])(.*)(\[/size\])}siU',
			'{(\[)(color)(=)([\'"]?)([a-z]*)(\\4])(.*)(\[/color\])}siU',            
            '{\[img\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img float=right\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img float=left\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img width=100\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
            '{\[quote\](\r\n|\r|\n)*(.+)\[/quote\]}siU',
            '{\[code\](\r\n|\r|\n)*(.+)\[/code\]}siU',
            '{\[yt\]([0-9a-zA-Z-_]{11})\[/yt]}siU',
            '{(\[)(gmap)(])((http|https)://)([^;<>\*\(\)"\s]*)(\[/gmap\])}siU',
            '{\[icq\]([0-9]{5,10})\[/icq\]}siU',
            '{\[sky\]([.0-9a-zA-Z-_]{6,32})\[/sky]}siU',
            '{\[aim\](\r\n|\r|\n)*([a-zA-Z0-9\._-]+@(([a-zA-Z0-9_-])+\.)+[a-z]{2,4})\[/aim\]}siU',
            '{\[yim\](\r\n|\r|\n)*([a-zA-Z0-9\._-]+@(([a-zA-Z0-9_-])+\.)+[a-z]{2,4})\[/yim\]}siU'
        );
		
        $testo_sostituisci = array(
            '<a href="mailto:\\2">\\2</a>',
            '<a href="mailto:\\1">\\2</a>',
            '<a href="\\4\\6" target="_blank">\\4\\6</a>',
            '<a href="\\5\\7" target="_blank">\\9</a>',
            '<a href="\\4\\6" target="_blank">\\4\\6</a>',
            '<a href="\\5\\7" target="_blank">\\9</a>',
            '<span style="font-size: \\5pt;">\\7</span>',
			'<span style="color: \\5;">\\7</span>',                 
            '<img src="\\2" alt="img" title="" />',
			'<img src="\\2" alt="img" title="" style="float:right" />',
			'<img src="\\2" alt="img" title="" style="float:left" />',
			'<img src="\\2" alt="img" title="" style="width:100%" />',
            '<div style="background-color:#FFFFFF; margin:0 auto; width:100%;" class="text2"><b>cityzone</b></div><div style="background-color:#F9F9F9; margin:0 auto; width:98%; height: auto; border: 1px solid #DEE3E7; padding: 3px;" class="text2">\\2</div>',
            '<div style="background-color:#FFFFFF; margin:0 auto; width:100%;" class="text2"><b>codice</b></div><div style="background-color:#F9F9F9; width: 98%; height: auto; padding: 3px; line-height: 7px; border: 1px solid #E1E1E1; white-space: nowrap; overflow: auto;" class="text"><pre>\\2</pre></div>',
            '<object width="320" height="265"><param name="movie" value="http://www.youtube.com/v/\\1&hl=it&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/\\1&hl=it&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="265"></embed></object>',
            '<iframe src="\\4\\6" width="400" height="300" frameborder="0" style="border:0"></iframe>',
            '<img src="http://web.icq.com/whitepages/online?icq=\\1&img=5" alt="" title="ICQ" />\\1',
            '<img src="http://mystatus.skype.com/smallicon/\\1" alt="" title="Skype" /><a href="skype:\\1?call">\\1</a>',
            '<img src="http://nagaoreishi.com/news/images/aim.png" alt="" title="AIM" />\\2',
            '<img src="http://nagaoreishi.com/news/images/yim.gif" alt="" title="Yahoo! Messenger" />\\2'
        );

//$testo = "[img]http://nagareishi.com/image/bigimage.jpg[/img]";
//$testo = "[img float=right]http://nagareishi.com/image/bigimage.jpg[/img]";
$testo = "[img width=100]http://nagareishi.com/image/bigimage.jpg[/img]";

$testo = preg_replace($testo_cerca, $testo_sostituisci, $testo);
echo "<input type='text' value='" .$testo . "' style='width:600px;'/>";
		
?>
<div><b>&#7915;&#7915;&#7915;&#7915;thi&#7879;nthi&#7879;nthi&#7879;nthi&#7879;n</b></div>
<?php
require_once ('/news/config.php');
require_once ('/news/admin/functions.php');
//require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);

//$orig = "I'll \"walk\" the <b>
$orig2 = "&#7915;&#7915;&#7915;&#7915;thi&#7879;nthi&#7879;nthi&#7879;nthi&#7879;n";
$orig = " \"n\" ";
$a = mysqli_real_escape_string($db, htmlentities(htmlspecialchars($orig, ENT_QUOTES, "ISO-8859-1" ), ENT_QUOTES, "ISO-8859-1" ));

$b = html_entity_decode($a, ENT_QUOTES, "ISO-8859-1");

echo "a:" . $a; // I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now

echo "<br/> b: " . $b; // I'll "walk" the <b>dog</b> now
if(isset($_POST['testo'])){
	echo "<br/>" . $_POST['testo'];
	echo "<br/>htmlentity:" . htmlentities( $_POST['testo'], ENT_XHTML, "ISO-8859-1" );
}
?>

<form method="post" action="test8.php" enctype="multipart/form-data" name="input_form"> 
	<textarea cols="118" rows="24" name="testo" id="testo" tabindex="2"></textarea>
	<input type="submit" value="goi" name="submit" style="font-weight: bold;" tabindex="3" />
</form>

<?php echo ;?>
</body>
</html>
