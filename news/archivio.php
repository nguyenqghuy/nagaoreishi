<?php 

//includo il file di configurazione
require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/admin/functions.php');
require_once (dirname(__FILE__) . '/lang/' . $language . '.php');

//connessione a mysql
$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);

//estraggo alcune impostazioni
$conf = @mysqli_query($db, "SELECT nome_sito, url_sito, max_archivio, max_archivio_parole, sfondo_titolo, sfondo_notizia, sfondo_strumenti, larghezza, nuova_news_day, formato_data FROM `$tab_config`");
$rowconf = @mysqli_fetch_array($conf);

//suddivisione news per pagine
$rec_page = $rowconf['max_archivio'];
$start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;

if (isset($_GET['autore']) && preg_match('/^[0-9]{1,5}$/', $_GET['autore'])) {
    $autore = abs(floor(intval($_GET['autore'])));
    $get_autore = "&amp;autore=" . abs(floor(intval($_GET['autore'])));
    $q_autore = "WHERE nu.user_id=$autore";
}
else {
    $autore = NULL;
    $get_autore = NULL;
    $q_autore = NULL;
}

if (isset($_GET['categoria']) && preg_match('/^[0-9]{1,3}$/', $_GET['categoria'])) {
    $categoria = abs(floor(intval($_GET['categoria'])));
    $get_categoria = "&amp;categoria=" . abs(floor(intval($_GET['categoria'])));
    $q_categoria = "WHERE nt.id_cat=$categoria";
}
else {
    $categoria = NULL;
    $get_categoria = NULL;
    $q_categoria = NULL;
}

//ordinamento news

if (isset($_GET['sortby']) && preg_match('/^[1-6]{1}$/', $_GET['sortby'])) {
    $sortby = abs(floor(intval($_GET['sortby'])));
    $get_sortby = "&amp;sortby=$sortby";
    
    switch ($sortby) {
        case 1:
            $q_sortby = 'ORDER BY nt.letture DESC';
            $view_letture = $lang['arch_letture'] . ' <span style="font-weight:bold;">';
            $view_letture2 = '</span>';
            $view_commenti = $lang['arch_commenti'];
            $view_commenti2 = '';
        break;
        case 3:
            $q_sortby = 'ORDER BY nt.data_pubb DESC';
            $view_letture = $lang['arch_letture'];
            $view_letture2 = '';
            $view_commenti = $lang['arch_commenti'];
            $view_commenti2 = '';
        break;
        case 4:
            $q_sortby = 'ORDER BY nu.nome_cognome ASC';
            $view_letture = $lang['arch_letture'];
            $view_letture2 = '';
            $view_commenti = $lang['arch_commenti'];
            $view_commenti2 = '';
        break;
        case 5:
            $q_sortby = 'ORDER BY nca.nome_categoria ASC';
            $view_letture = $lang['arch_letture'];
            $view_letture2 = '';
            $view_commenti = $lang['arch_commenti'];
            $view_commenti2 = '';
        break;
        case 6:
            $q_sortby = 'ORDER BY TotaleCommenti DESC';
            $view_letture = $lang['arch_letture'];
            $view_letture2 = '';
            $view_commenti = $lang['arch_commenti'] . ' <span style="font-weight:bold;">';
            $view_commenti2 = '</span>';
        break;
        default:
            $q_sortby = 'ORDER BY nt.data_pubb DESC';
            $view_letture = $lang['arch_letture'];
            $view_letture2 = '';
            $view_commenti = $lang['arch_commenti'];
            $view_commenti2 = '';
    }
}
else {
    $sortby = NULL;
    $q_sortby = "ORDER BY nt.data_pubb DESC";
    $get_sortby = NULL;
    $view_letture = $lang['arch_letture'];
    $view_letture2 = '';
    $view_commenti = $lang['arch_commenti'];
    $view_commenti2 = '';
}

if ($rowconf['max_archivio_parole'] == 0) {
    $result = @mysqli_query($db, "SELECT nt.id, nt.titolo, nt.shortdescription, nt.friendly_url, nt.data_pubb, nt.letture, nt.immagine, nu.nome_cognome, nca.nome_categoria, nca.img_categoria, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id AND nco.approvato=1) AS TotaleCommenti FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat $q_autore $q_categoria AND nt.news_approvata = 1 AND nt.data_pubb < " . time() . " $q_sortby LIMIT $start,$rec_page");
}
else {
    $result = @mysqli_query($db, "SELECT nt.id, nt.titolo, nt.shortdescription, nt.friendly_url, LEFT(nt.testo, " . $rowconf['max_archivio_parole'] . ") AS testo, nt.data_pubb, nt.letture, nt.immagine, nu.nome_cognome, nca.nome_categoria, nca.img_categoria, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id AND nco.approvato=1) AS TotaleCommenti FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat $q_autore $q_categoria AND nt.news_approvata = 1 AND nt.data_pubb < " . time() . " $q_sortby LIMIT $start,$rec_page");

    //sostituisco i codici di formattazione
    $testo_cerca = array(
        '{\[ul]}',
        '{\[li]}',
        '{\[/li]}',
        '{\[/ul]}',
        '{&amp;}',
        '{\[b]}',
        '{\[i]}',
        '{\[u]}',
        '{\[/b]}',
        '{\[/i]}',
        '{\[/u]}',
        '{\[img\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img float=right\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img float=left\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img width=100\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
			'{\[img float=left width=50\](\r\n|\r|\n)*((http|https)://([^;<>\*\(\)\"\s]+)|[a-zA-Z0-9/\\\._\- ]+)\[/img\]}siU',
        '{\[email\](\r\n|\r|\n)*([a-zA-Z0-9\._-]+@(([a-zA-Z0-9_-])+\.)+[a-z]{2,4})\[/email\]}siU',
        '{\[email=(\w[\w\-\.\+]*?@\w[\w\-\.]*?\w\.[a-zA-Z]{2,4})\](.+)?\[\/email\]}siU',
        '{(\[)(url)(])((http|ftp|https)://)([^;<>\*\(\)"\s]*)(\[/url\])}siU',
        '{(\[)(url)(=)([\'"]?)((http|ftp|https)://)([^;<>\*\(\)"\s]*)(\\4])(.*)(\[/url\])}siU',
        '{(\[)(callto)(])((callto):)([^;<>\*\(\)"\s]*)(\[/callto\])}siU',
        '{(\[)(callto)(=)([\'"]?)((callto):)([^;<>\*\(\)"\s]*)(\\4])(.*)(\[/callto\])}siU',
        '{(\[)(size)(=)([\'"]?)([0-9]*)(\\4])(.*)(\[/size\])}siU',
		'{(\[)(color)(=)([\'"]?)([a-z]*)(\\4])(.*)(\[/color\])}siU',
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
        '',
        '',
        '',
        '',
        '&',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '\\2',
		'\\2',
		'\\2',
		'\\2',
		'\\2',
        '\\1',
        '\\4\\6',
        '\\9',
        '\\4\\6',
        '\\5\\7',
        '\\7',
        '\\7',
        '\\2',
        '\\2',
        '[Video: youtube.com/watch?v=\\1]',
		'\\4\\6',
        '\\1',
        '\\1',
        '\\2',
        '\\2'
    );
}



?>	



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tin tuc cap nhat ve nam linh chi NAGAO</title>
<link rel="alternate" href="http://www.namlinhchinagao.com/news/" hreflang="vi-vn" />
<link rel="alternate" type="application/rss+xml" title="Feed RSS News" href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/rss.php'; ?>" /> 
<meta name="description" content="Tin tuc cap nhat ve nam linh chi NAGAO, su dung nam linh chi hang ngay de lam dep co the va bao ve suc khoe cua gia dinh ban." />
<link rel="canonical" href="http://www.namlinhchinagao.com/news/" />
<meta http-equiv="content-language" content="vi">
<meta property="og:locale" content="vi_VN" />
<meta property="og:url" content="http://www.namlinhchinagao.com/news/" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Tin tuc cap nhat ve nam linh chi NAGAO" />
<meta property="og:description" content="Tin tuc cap nhat ve nam linh chi NAGAO, su dung nam linh chi hang ngay de lam dep co the va bao ve suc khoe cua gia dinh ban." />
<meta property="og:image" content="http://www.namlinhchinagao.com/picture/linhchi-nagao.jpg" />

<meta name="robots" content="index,follow" />

<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/archivio.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/javascript/common.js"> </script>
</head>

<body>
<?php include "../php/header.php"; ?>
<!--Phần thân-->
<h1 class="col10">TIN TỨC SỰ KIỆN</h1>
<div class="col10" id="NewsContainer">
<?php	
//stampo l'elenco delle news

while ($row = @mysqli_fetch_array($result)) {

    //seleziono il formato data
    $data = GetDateStr($rowconf['formato_data'], $row);
	$LabelNews = ((time() - $row['data_pubb']) <= 60 * 60 * 24 * $rowconf['nuova_news_day']) ? 'Tin Mới | ' : '';
	
?>
	<div class="col4">
		<img src="<?php echo $row['immagine']?>" alt="<?php echo $row['titolo']?>"/>
		<p><?php echo ToUnicode($row['titolo']);?></p>
		<p><?php echo preg_replace($testo_cerca, $testo_sostituisci, $row['shortdescription']) . "..."?> </p>
		<a href="/news/<?php echo $row['friendly_url'];?>/"></a>
		<div><?php echo $LabelNews ?> <?php echo  $data?></div>
	</div>
<?php 
}
?>
</div>
<?php

//paginazione
$sql_num_totale = @mysqli_query($db, "SELECT COUNT(nt.id) AS NumTotale FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id $q_autore $q_categoria AND nt.news_approvata = 1 AND nt.data_pubb < " . time() . "");
$num_totale_riga = @mysqli_fetch_array($sql_num_totale);
$numero_pagine = @ceil($num_totale_riga['NumTotale'] / $rec_page);
$pagina_attuale = @ceil(($start / $rec_page) + 1);
?>
<div class="col10" id="Pagedivine"><b>Tất cả: <?php echo $num_totale_riga['NumTotale'];?></b>
	<?php echo page_bar("archivio.php?$get_autore$get_categoria$get_sortby", $pagina_attuale, $numero_pagine, $rec_page);?>
</div>

<!--Kết thúc phần thân-->
<?php include "../php/footer.php"; mysqli_close($db);?>


</body>
</html>
