<?php

header('Content-type: text/html; charset=utf-8');

//se l'id è valido visualizzo la notizia
//$get_id = (isset($_GET['id']) && preg_match('/^[0-9]{1,8}$/', $_GET['id'])) ? intval($_GET['id']) : 0;
if(isset($_GET['url'])){
	$get_url =  $_GET['url'];
}else{
	header('Location: /news/');
    exit();
  // $testo = "ko có URL"; 
}

if($get_url == 'admin'){
	header('Location: /news/admin/login.php');
   exit();
}
//includo il file di configurazione
require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/admin/functions.php');
require_once (dirname(__FILE__) . '/lang/' . $language . '.php');
require_once (dirname(__FILE__) . '/../php/constant.php');

//connessione a mysql
$servername = DATASERVER;
$dbname = DATABASE;
$myerror = "";

try{
	$db = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)	{
	$myerror = "Could not connect: " . $e->getMessage();
}
if($db){	

	//$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);
	
	$query_msg = NULL;
	$campi_vuoti = NULL;
	$errore_email = NULL;
	$errore_captcha = NULL;
	$link_inserisci = NULL;
	$autore_value = NULL;
	$email_value = NULL;
	$sito_value = NULL;
	$commento_value = NULL;
	
	//seleziono la notizia richiesta dall'utente
	$sql_query = "SELECT * FROM `news_testi` WHERE `friendly_url`='$get_url' AND `news_approvata` = 1 AND `data_pubb` < " . time();
	//$sql_news = @mysqli_query($db, "SELECT * FROM `news_testi` WHERE `friendly_url`='$get_url' AND `news_approvata` = 1 AND `data_pubb` < " . time());
	/*$rownews = @mysqli_fetch_array($sql_news);
	*/

	try{	
		$stmt = $db->prepare($sql_query);
		$stmt->execute();
		$q_order = $stmt->fetchall();
		$rownews = $q_order[0];
	}
	catch(PDOException $e){
		$myerror .= "Cannot get Data to display:" . $e->getMessage();
	}


//if (@mysqli_num_rows($sql_news) == 0){
	if(!$rownews){
		// Ko có tin thì sẽ hiện như sau
		$db = NULL;
		header('Location: /news/');
	   exit();
	}

    
    $testo = html_entity_decode($rownews['testo'], ENT_QUOTES, "ISO-8859-1");

    //estraggo alcune configurazioni
	$sql_confquery = "SELECT nome_sito, url_sito, commenti_per_page, moderazione_commenti, sfondo_titolo, sfondo_notizia, sfondo_strumenti, larghezza, larghezza_pager, larghezza_commenti, formato_data FROM `$tab_config`";
   // $sql_conf = @mysqli_query($db, "SELECT nome_sito, url_sito, commenti_per_page, moderazione_commenti, sfondo_titolo, sfondo_notizia, sfondo_strumenti, larghezza, larghezza_pager, larghezza_commenti, formato_data FROM `$tab_config`");
    //$rowconf = @mysqli_fetch_array($sql_conf);
	try{	
		$stmt = $db->prepare($sql_confquery);
		$stmt->execute();
		$q_order = $stmt->fetchall();
		$rowconf = $q_order[0];
	}
	catch(PDOException $e){
		$myerror .= "Cannot get Data config to display:" . $e->getMessage();
	}

    //suddivisione commenti per x pagine
    $rec_page = $rowconf['commenti_per_page'];
    $start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;
    $Title = $rownews['titolo'];
	$description = $rownews['description'];
	$data = GetDateStr($rowconf['formato_data'], $rownews);
	//mysqli_close($db);
	//Get List News
	$ListQuery = "SELECT nt.titolo, nt.friendly_url FROM `news_testi` nt WHERE nt.news_approvata = 1 AND nt.data_pubb < " . $rownews['data_pubb'] . " ORDER BY nt.data_pubb DESC LIMIT 0,6";
	try{	
		$stmt = $db->prepare($ListQuery);
		$stmt->execute();
		$ListNews = $stmt->fetchall();
	}
	catch(PDOException $e){
		$myerror .= "Cannot get ListNews to display:" . $e->getMessage();
	}
}
$db = NULL;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $Title . ' - ' . $rowconf['nome_sito']; ?></title>
<link rel="alternate" href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/'; ?>" hreflang="vi-vn" />
<meta name="description" content="<?php echo $description . ' - ' . $rowconf['nome_sito']; ?>" />
<link rel="canonical" href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/'; ?>" />
<meta http-equiv="content-language" content="vi">
<meta property="og:locale" content="vi_VN" />

<meta property="og:url" content="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/'; ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $Title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image" content="<?php echo $rownews['immagine'] ?>" />

<meta name="robots" content="index,follow" />

<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/view.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/javascript/common.js"> </script>
</head>

<body>
<?php include "../php/header.php"; ?>
<!--Phần thân-->
	<div id="Breadcum" class="col11">
		<a href="http://namlinhchinagao.com" title="Nam linh chi NAGAO bao ve suc khoe cua ban" id="trang-chu01">Trang chủ</a> ► <a href="/news/" title="Tin tuc moi nhat" id="news01">Tin tức</a> ► <a><?php echo $Title ?></a> 
	</div>
	<h1 class="col10"><?php echo $Title ?></h1>
	<p class="col10"><?php echo $data;?> </p>
	<div class="col10" id="NewsContainer">
	<?php 
	
	echo $myerror;
	echo bbCode($testo, $rownews['nosmile']);
	//Hiển thị phần share
	echo '<br /><div id="sbookmark"><div>' . $lang['condividi'] . ': ';
	?>
		<div class="fb-share-button" data-href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/'; ?>" data-layout="button"></div>
	<?php
	echo '<a href="https://twitter.com/intent/tweet?source=webclient&amp;text=' . $rownews['titolo'] . ' - ' . $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/" target="_blank"><img src="' . $img_path . '/twitter.png" border="0" alt="twitter" title="Twitter" /></a> <a href="https://plus.google.com/share?url=' . $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><img src="' . $img_path . '/gplus.png" border="0" alt="google+" title="Google+" /></a> <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='  . $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/&amp;title=' . $rownews['titolo'] . '&amp;summary=' . $rownews['titolo'] . '" target="_blank"><img src="' . $img_path . '/linkedin.gif" border="0" alt="linkedin" title="LinkedIn" /></a> <a href="http://reddit.com/submit?url='  . $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/&amp;title=' . $rownews['titolo'] . '" target="_blank"><img src="' . $img_path . '/reddit.gif" border="0" alt="reddit" title="Reddit" /></a> <a href="http://www.stumbleupon.com/submit?url='  . $rowconf['url_sito'] . '/' . $news_dir . '/' . $rownews['friendly_url'] . '/" target="_blank"><img src="' . $img_path . '/su.png" border="0" alt="su" title="StumbleUpon" /></a></div></div> ';


	?>
		<div>
			<p>We Specialize In:</p><br/>
			Tags: <a href="../nam-linh-chi/" title="Tai lieu tac dung cua nam linh chi - Reishi">nam linh chi</a>, <a href="../ung-thu-vom-hong/" title="Tac dung cua nam linh chi len ung thu (ung thu vom hong) - reishi heal cancer">ung thu vom hong</a>
			, <a href="/news/benh-tieu-duong-2/" title="tac dung cua nam linh chi len benh tieu duong">benh tieu duong</a>	
			, <a href="/news/tac-dung-cua-nam-linh-chi-len-suc-khoe/" title="tac dung cua nam linh chi len suc khoe">suc khoe</a>	
			, <a href="/news/tac-dung-cua-nam-linh-chi-chong-lai-tuoi-gia-ung-thu-the-nao/" title="tac dung cua nam linh chi chong lai tuoi gia, ung thu, benh tieu duong, chong di ung, hen xuyen">tac dung cua nam linh chi</a>	
		</div>
		<div>
		Bài viết khác
			<ul>
			<?php 
			foreach($ListNews as $key => $RowNews){
			
			?>
				<li><a href="../<?php  echo $RowNews['friendly_url'];?>/"><?php echo $RowNews['titolo']; ?></a></li>
				
			<?php 
			}
			?>
			</ul>
		
		</div>
	</div>
	<!--Kết thúc phần thân-->
	<?php include "../php/footer.php"; ?>
	
</body>
</html>
