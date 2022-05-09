<?php

/*****************************************************************
 *  Spacemarc News
 *  Author and copyright (C): Marcello Vitagliano
 *  Web site: http://www.spacemarc.it
 *  License: GNU General Public License
 *
 *  This program is free software: you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation, either version 3
 *  of the License, or (at your option) any later version.
 *****************************************************************/
session_start();
header('Content-type: text/html; charset=ISO-8859-1');

//includo i file di configurazione
require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/admin/functions.php');
require_once (dirname(__FILE__) . '/lang/' . $language . '.php');

//connessione a mysql
$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);

//se c'è l'id della notizia inviato via GET ed è di tipo numerico ed è presente in tabella visualizzo la notizia
$get_id = (isset($_GET['id']) && preg_match('/^[0-9]{1,8}$/', $_GET['id'])) ? intval($_GET['id']) : 0;

//estraggo la notizia selezionata via GET
$sql_news = @mysqli_query($db, "SELECT nt.id, nt.titolo, nt.testo, nt.data_pubb, nt.immagine, nt.nosmile, nu.nome_cognome, nca.nome_categoria FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.id=$get_id AND nt.news_approvata = 1 AND nt.data_pubb < " . time() . "");
$rownews = @mysqli_fetch_array($sql_news);

if (@mysqli_num_rows($sql_news) > 0) {
    $testo = nl2br($rownews['testo']);
    $img_view = ($rownews['immagine'] != '') ? '<div id="imgap" class="imageap"><img src="' . $rownews['immagine'] . '" border="1" alt="" width="96" height="86" /></div>' : NULL;

    //estraggo alcune impostazioni
    $sql_conf = @mysqli_query($db, "SELECT nome_sito, url_sito, formato_data FROM `$tab_config`");
    $rowconf = @mysqli_fetch_array($sql_conf);
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">        
  <head>              
    <title>                    
      <?php echo $rownews['titolo'] . ' - ' . $lang['pagina_stampa']; ?>
    </title>              
    <link rel="stylesheet" href="print.css" type="text/css" />              
    <link rel="alternate" type="application/rss+xml" title="Feed RSS News" href="<?php echo $rowconf['url_sito'] . '/' . $news_dir . '/rss.php'; ?>" />
<script language="JavaScript" src="javascript.js" type="text/JavaScript"></script>        
  </head>        
  <body>              
    <div id="container_print">                       
      <div id="logo">                               
        <a href="/" title="Home Page">                                
          <img src="/imgs/logo.gif" alt="LOGO" border="0" /></a>                       
      </div>                       
      <div id="tools">	                           
        <span id="nascondi"><a href="javascript:;" onclick="img_hide();"><?php echo $lang['senza_immagini']; ?></a></span> 
        <span id="mostra" style="display: none;"><a href="javascript:;" onclick="img_show();"><?php echo $lang['con_immagini']; ?></a></span> - 
        <a href="javascript:;" onclick="self.print();"><?php echo $lang['stampa']; ?></a>                    
      </div><br /><br /><br /><br /><br />                       
      <div id="news_print">
<?php

    //seleziono il formato data
	$data = GetDateStr($rowconf['formato_data'], $rownews);

/*    date_default_timezone_set("Asia/Ho_Chi_Minh");
    switch ($rowconf['formato_data']) {
        case 1:
            $data = strftime("%a %d %b %Y, %H:%M", $rownews['data_pubb']);
        break;
        case 2:
            $data = str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M", $rownews['data_pubb']));
        break;
        case 3:
            $data = strftime("%d/%m/%Y, %H:%M", $rownews['data_pubb']);
        break;
        case 4:
            $data = strftime("%d %b %Y, %H:%M", $rownews['data_pubb']);
        break;
        case 5:
            $data = strftime("%d %B %Y, %H:%M", $rownews['data_pubb']);
        break;
        case 6:
            $data = strftime("%m/%d/%Y, %I:%M %p", $rownews['data_pubb']);
        break;
        case 7:
            $data = strftime("%B %d, %Y %I:%M %p", $rownews['data_pubb']);
        break;
        case 8:
            $data = strftime("%I:%M %p %B %d, %Y", $rownews['data_pubb']);
        break;
    }
*/    echo '
<b>' . $lang['titolo'] . '</b>: ' . $rownews['titolo'] . ' <b>' . $lang['autore'] . '</b>: ' . $rownews['nome_cognome'] . '<br />
<b>' . $lang['data'] . '</b>: ' . $data . ' <b>' . $lang['categoria'] . '</b>: ' . $rownews['nome_categoria'] . ' <b>URL</b>: ' . $rowconf['url_sito'] . '/' . $news_dir . '/view.php?id=' . $rownews['id'] . '<br /><br /><br />' . $img_view . bbCode($testo) . ' ';
?>                          
        <p>&nbsp;                           
        </p><br /><br />                          
        <div id="note">Copyright &copy;            
          <?php echo $rowconf['nome_sito']; ?> - <?php echo $lang['copyright']; ?>
        </div>                    
      </div>              
    </div>        
  </body>
</html>
<?php
    mysqli_close($db);
}
else {
    echo "No news";
}
?>