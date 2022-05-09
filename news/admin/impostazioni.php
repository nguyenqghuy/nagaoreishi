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

//calcolo il tempo di generazione della pagina (1a parte)
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];

//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/functions.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);
check_login();

//se NON sono un amministratore e voglio visualizzare questa pagina, redirigo al proprio profilo

if ($_SESSION['livello_id'] != 1) {
    header('Location: ' . $dir_admin . '/profilo_utente.php');
    exit();
}
$sql_config = mysqli_query($db, "SELECT * FROM `$tab_config`");
$config_val = mysqli_fetch_assoc($sql_config);
$errore_sito = NULL;
$errore_news = NULL;
$errore_sfondo = NULL;
$update_msg = NULL;
$update_error = NULL;
$errore_tabella = NULL;
$opt_msg = NULL;
$backup_result = NULL;
$moderazione_commenti_checked = ($config_val['moderazione_commenti'] == 0) ? NULL : 'checked="checked"';
$bck_name = 'backup_' . substr(md5($db_password), 0, 6); //nome del file di backup

switch ($config_val['formato_data']) {
    case 1:
        $fd_selected1 = 'selected="selected"';
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 2:
        $fd_selected1 = NULL;
        $fd_selected2 = 'selected="selected"';
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 3:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = 'selected="selected"';
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 4:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = 'selected="selected"';
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 5:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = 'selected="selected"';
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 6:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = 'selected="selected"';
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
    break;
    case 7:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = 'selected="selected"';
        $fd_selected8 = NULL;
    break;
    case 8:
        $fd_selected1 = NULL;
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = 'selected="selected"';
    break;
    default:
        $fd_selected1 = 'selected="selected"';
        $fd_selected2 = NULL;
        $fd_selected3 = NULL;
        $fd_selected4 = NULL;
        $fd_selected5 = NULL;
        $fd_selected6 = NULL;
        $fd_selected7 = NULL;
        $fd_selected8 = NULL;
}

//aggiornamento impostazioni

if (isset($_POST['submit'])) {
    
    if (trim($_POST['nome_sito']) == '' || trim($_POST['url_sito']) == '') {
        $errore_sito = '<div id="error">' . $lang['required'] . '</div><br />';
    }
    else {
        $errore_sito = NULL;
    }
    
    if (!preg_match('/^[0-9]{1,3}$/', $_POST['perpage']) || !preg_match('/^[0-9]{1,5}$/', $_POST['firstwords']) || !preg_match('/^[0-9]{1,3}$/', $_POST['perpagesearch']) || !preg_match('/^[0-9]{1,3}$/', $_POST['commenti_per_page']) || !preg_match('/^[0-9]{1,3}$/', $_POST['maxinclude']) || !preg_match('/^[0-9]{1,5}$/', $_POST['fwinclude']) || !preg_match('/^[0-9]{1,5}$/', $_POST['width']) || !preg_match('/^[0-9]{1,3}$/', $_POST['maxgestione']) || !preg_match('/^[0-9]{1,3}$/', $_POST['maxutenti']) || !preg_match('/^[0-9]{1,3}$/', $_POST['maxpersonali']) || !preg_match('/^[0-9]{1,5}$/', $_POST['width_pager']) || !preg_match('/^[0-9]{1,5}$/', $_POST['larghezza_commenti']) || !preg_match('/^[0-9]{1,3}$/', $_POST['nuova_news_day']) || !preg_match('/^[0-9]{1,3}$/', $_POST['maxcommenti']) || !preg_match('/^[0-9]{1,4}$/', $_POST['maxfilesize'])) {
        $errore_news = '<div id="error">' . $lang['solo_numeri'] . '</div><br />';
    }
    else {
        $errore_news = NULL;
    }
    
    if (!preg_match('/^#([a-f0-9]{6})/i', $_POST['coltitolo']) || !preg_match('/^#([a-f0-9]{6})/i', $_POST['colcorpo']) || !preg_match('/^#([a-f0-9]{6})/i', $_POST['coltools'])) {
        $errore_sfondo = '<div id="error">' . $lang['colore_corretto'] . '</div><br />';
    }
    else {
        $errore_sfondo = NULL;
    }
    $moderazione_commenti = (isset($_POST['moderazione_commenti'])) ? 1 : 0;
    
    if ($errore_sito == NULL && $errore_news == NULL && $errore_sfondo == NULL) {
        $trova = array(
            '\\',
            '\'',
            '"',
            '<',
            '>'
        );
        $_POST['nome_sito'] = str_replace($trova, "", trim($_POST['nome_sito']));
        $_POST['url_sito'] = str_replace($trova, "", trim($_POST['url_sito']));

        //aggiorno il nome dei livelli
        
        for ($i = 0;$i < 2;++$i) {
            
            if (!preg_match('/^([a-zA-Z0-9-]{1,14})$/', $_POST['nome_livello'][$i]) || !preg_match('/^([1|3]{1})$/', $_POST['livello_id'][$i])) {
                
                break;
            }
            else {
                mysqli_query($db, "UPDATE `$tab_livelli` SET nome_livello = '" . trim($_POST['nome_livello'][$i]) . "' WHERE livello_id=" . $_POST['livello_id'][$i]);
            }
        }
        
        if (mysqli_query($db, "UPDATE `$tab_config` SET nome_sito='" . $_POST['nome_sito'] . "', url_sito='" . $_POST['url_sito'] . "', max_archivio=" . $_POST['perpage'] . ", max_archivio_parole=" . $_POST['firstwords'] . ", max_ricerche=" . $_POST['perpagesearch'] . ", commenti_per_page=" . $_POST['commenti_per_page'] . ", moderazione_commenti=" . $moderazione_commenti . ", max_tit_include=" . $_POST['maxinclude'] . ", max_parole_include=" . $_POST['fwinclude'] . ", sfondo_titolo='" . $_POST['coltitolo'] . "', sfondo_notizia='" . $_POST['colcorpo'] . "', sfondo_strumenti='" . $_POST['coltools'] . "', larghezza=" . $_POST['width'] . ", larghezza_pager=" . $_POST['width_pager'] . ", larghezza_commenti=" . $_POST['larghezza_commenti'] . ", max_gest_news=" . $_POST['maxgestione'] . ", max_utenti=" . $_POST['maxutenti'] . ", max_news_personali=" . $_POST['maxpersonali'] . ", formato_data=" . $_POST['formato_data'] . ", max_gest_comm=" . $_POST['maxcommenti'] . ", nuova_news_day=" . $_POST['nuova_news_day'] . ", max_file_size=" . $_POST['maxfilesize'] * 1024 . "")) {
            $update_msg = '<div align="center"><span class="text"><b>' . $lang['conf_updated'] . '</b></span> <img src="' . $img_path . '/attendi.gif" title="" alt="" /></div><br />';
            header("Refresh: 2; url=impostazioni.php");
        }
        else {
            $update_error = '<div id="error">' . $lang['conf_problem'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
        }
    }
}
elseif (isset($_POST['backup'])) {
    
    if (!isset($_POST['selected_tbl'])) {
        $errore_tabella = '<div id="error">' . $lang['err_tabella'] . '</div><br />';
    }
    else {
        $errore_tabella = NULL;
        
        require_once ("backup.php");
        $dir = '../' . $file_dir;
		$backup_result = backup_database($dir, $bck_name, $db_host, $db_user, $db_password, $db_name);
		
    }
}
elseif (isset($_GET['action']) && (strcmp($_GET['action'], 'opt') == 0) && isset($_GET['tab'])) {

    //posso ottimizzare solo le tabelle delle news
    $tab_permesse = array(
        $tab_news,
        $tab_utenti,
        $tab_config,
        $tab_livelli,
        $tab_categorie,
        $tab_commenti,
        $tab_ban
    );
    
    if (!in_array($_GET['tab'], $tab_permesse)) {
        die("No table");
    }
    else {
        
        if (mysqli_query($db, "OPTIMIZE TABLE `" . $_GET['tab'] . "`")) {
            $opt_msg = '<div id="success">' . $lang['optimized_ok'] . '</div><br />';
        }
        else {
            $opt_msg = '<div id="error">' . $lang['optimized_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
        }
    }
}
elseif (isset($_GET['action']) && (strcmp($_GET['action'], 'bck_canc') == 0)) {
	@unlink('../' . $file_dir . '/' . $bck_name . '.sql.gz');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">     
  <head>         
    <title><?php echo $lang['impostazioni_pagina']; ?>         
    </title>         
    <link rel="stylesheet" href="../style.css" type="text/css" />		 
<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>   
<script language="JavaScript" type="text/JavaScript"> 
<!--
//disabilito il tasto Invio dal form
function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {
return false;
}
}
document.onkeypress = stopRKey;
//--> 
</script>      
  </head>     
  <body>
<?php
require_once ("menu.php");
echo $errore_sito;
echo $errore_news;
echo $errore_sfondo;
echo $update_msg;
echo $update_error;
echo $errore_tabella;
echo $opt_msg;
?>         
    <form action="impostazioni.php" method="post" name="config">             
      <table width="100%" align="center" style="border: 3px solid #DDDDDD;" cellpadding="4" cellspacing="2" class="text">                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left" width="31%"><b><?php echo $lang['nome_url']; ?></b><br />                         
            <span class="text2">              
              <?php echo $lang['url_sito_descr']; ?>            
            </span></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text"><?php echo $lang['nome_sito']; ?>                           
            <input type="text" name="nome_sito" value="<?php echo $config_val['nome_sito']; ?>" size="28" maxlength="40" /> <?php echo $lang['url_sito']; ?>
            <input type="text" name="url_sito" value="<?php echo $config_val['url_sito']; ?>" size="28" maxlength="50" /></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['archivio_notizie']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">            
            <?php echo $lang['per_page']; ?>                           
            <input type="text" value="<?php echo $config_val['max_archivio']; ?>" name="perpage" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['lettere1']; ?>                           
            <input type="text" value="<?php echo $config_val['max_archivio_parole']; ?>" name="firstwords" maxlength="5" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['lettere2']; ?></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['ricerche_commenti']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">            
            <?php echo $lang['per_page_search']; ?>                           
            <input type="text" value="<?php echo $config_val['max_ricerche']; ?>" name="perpagesearch" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" />
            <label for="moderazione_commenti">
              <?php echo $lang['moderazione_commenti']; ?>
            </label>
            <input type="checkbox" id="moderazione_commenti" name="moderazione_commenti" <?php echo $moderazione_commenti_checked; ?> />  
            <?php echo $lang['commenti_per_pagina']; ?>  
            <input type="text" value="<?php echo $config_val['commenti_per_page']; ?>" name="commenti_per_page" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['ultime_notizie']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">            
            <?php echo $lang['max_include']; ?>                           
            <input type="text" value="<?php echo $config_val['max_tit_include']; ?>" name="maxinclude" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['lettere1']; ?>
            <input type="text" value="<?php echo $config_val['max_parole_include']; ?>" name="fwinclude" maxlength="5" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['lettere2']; ?></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['img_nuova_news']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">            
            <?php echo $lang['nuova_news_day']; ?>                           
            <input type="text" value="<?php echo $config_val['nuova_news_day']; ?>" name="nuova_news_day" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['colori_dimensioni']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">						             
            <?php echo $lang['col_titolo']; ?>               
            <input type="text" value="<?php echo $config_val['sfondo_titolo']; ?>" name="coltitolo" maxlength="7" size="7" />                           
            <?php echo $lang['col_news']; ?>                
            <input type="text" value="<?php echo $config_val['sfondo_notizia']; ?>" name="colcorpo" maxlength="7" size="7" />                           
            <?php echo $lang['col_tools']; ?>                
            <input type="text" value="<?php echo $config_val['sfondo_strumenti']; ?>" name="coltools" maxlength="7" size="7" /><br />                         
            <?php echo $lang['larghezza']; ?>               
            <input type="text" value="<?php echo $config_val['larghezza']; ?>" name="width" maxlength="5" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" />px                           
            <?php echo $lang['larghezza_nav']; ?>                
            <input type="text" value="<?php echo $config_val['larghezza_pager']; ?>" name="width_pager" maxlength="5" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" />px  						
            <?php echo $lang['larghezza_commenti']; ?>                
            <input type="text" value="<?php echo $config_val['larghezza_commenti']; ?>" name="larghezza_commenti" maxlength="5" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" />px </td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['admin_mostra_record']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text"><?php echo $lang['gestione_news']; ?>                           
            <input type="text" value="<?php echo $config_val['max_gest_news']; ?>" name="maxgestione" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['elenco_utenti']; ?>                               
            <input type="text" value="<?php echo $config_val['max_utenti']; ?>" name="maxutenti" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['news'] . ' ' . $lang['elenco_utenti']; ?>                               
            <input type="text" value="<?php echo $config_val['max_news_personali']; ?>" name="maxpersonali" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /> <?php echo $lang['commenti']; ?> 			
            <input type="text" value="<?php echo $config_val['max_gest_comm']; ?>" name="maxcommenti" maxlength="3" size="2" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" /></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['formato_data']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">
            <select name="formato_data">
<?php
echo '<option value="1" ' . $fd_selected1 . '>' . strftime("%a %d %b %Y, %H:%M") . '</option>';
echo '<option value="2" ' . $fd_selected2 . '>' . str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M")) . '</option>';
echo '<option value="3" ' . $fd_selected3 . '>' . strftime("%d/%m/%Y, %H:%M") . '</option>';
echo '<option value="4" ' . $fd_selected4 . '>' . strftime("%d %b %Y, %H:%M") . '</option>';
echo '<option value="5" ' . $fd_selected5 . '>' . strftime("%d %B %Y, %H:%M") . '</option>';
echo '<option value="6" ' . $fd_selected6 . '>' . strftime("%m/%d/%Y, %I:%M %p") . '</option>';
echo '<option value="7" ' . $fd_selected7 . '>' . strftime("%B %d, %Y %I:%M %p") . '</option>';
echo '<option value="8" ' . $fd_selected8 . '>' . strftime("%I:%M %p %B %d, %Y") . '</option>';
?>                         
            </select></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['dim_upload_allegati']; ?></b></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">                         
            Max KiB <input type="text" value="<?php echo $config_val['max_file_size'] / 1024; ?>" name="maxfilesize" maxlength="4" size="3" onkeypress="return onlynumbers(event,'0123456789')" onfocus="this.select()" />
			<?php echo $lang['files_orfani_descr']; ?>:              
            <a href="javascript:;" onclick="window.open('files_orfani.php', '', 'width=650, height=450, resizable=1, scrollbars=1, location=1, status=1');" title="[Popup]"><?php echo $lang['file']; ?></a></td>                     
        </tr>        
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['nome_livelli']; ?></b><br />          
            <span class="text2"><?php echo $lang['nome_livelli_descr']; ?> (a-zA-Z0-9-1,14)
            </span></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">            
<?php
$sql_livelli = mysqli_query($db, "SELECT livello_id, nome_livello FROM `$tab_livelli`");

while ($livelli_val = mysqli_fetch_array($sql_livelli)) {
    echo ' <input type="text" name="nome_livello[]" value="' . $livelli_val['nome_livello'] . '"  maxlength="14" size="12" />';
    echo ' <input type="hidden" name="livello_id[]" value="' . $livelli_val['livello_id'] . '" />';
}
?>            </td>                     
        </tr>                           
        <tr>                            
          <td bgcolor="#DEE3E7" align="left"><b><?php echo $lang['info_backup']; ?></b><br /></td>                            
          <td bgcolor="#EEEEEE" align="left" class="text">
<?php

//informazioni su PHP, MySQL, Web Server, OS
$phpversion = (!@phpversion()) ? '<b>PHP</b>: N/A' : '<b>PHP</b>: ' . phpversion();
$mysqli_get_server_info = (!@mysqli_get_server_info($db)) ? '<b>MySQL</b>: N/A' : '<b>MySQL</b>: ' . mysqli_get_server_info($db);
$apache_get_version = '<b>Web Server</b>: ' . @$_SERVER['SERVER_SOFTWARE'];
$php_uname = (!@php_uname()) ? '<b>OS</b>: N/A' : '<b>OS</b>: ' . php_uname();
echo $phpversion . ' ' . $mysqli_get_server_info . ' ' . $apache_get_version . ' ' . $php_uname . '<br /><br />';

//ricavo le info sulle tabelle

if (@mysqli_get_server_info($db) >= 5) {
    $status_table = mysqli_query($db, "SHOW TABLE STATUS WHERE Name IN ('$tab_news', '$tab_utenti', '$tab_config', '$tab_livelli', '$tab_categorie', '$tab_commenti', '$tab_ban')");
}
else {
    $status_table = mysqli_query($db, "SHOW TABLE STATUS LIKE 'news_%'");
}

while ($status_tb = mysqli_fetch_array($status_table)) {
    $eccesso = ($status_tb['Data_free'] > 0) ? ' - <span style="color: rgb(255, 0, 0);">' . $lang['in_eccesso'] . ' ' . round($status_tb['Data_free'] / 1024, 2) . ' KiB</span>: <a href="impostazioni.php?action=opt&amp;tab=' . $status_tb['Name'] . '" title="SQL: OPTIMIZE TABLE">' . $lang['ottimizza'] . '</a>' : NULL;
    $size = $status_tb['Data_length'] + $status_tb['Index_length'];

    //converto i bytes in KiB e MiB
    $tipo = 'bytes';
    
    if ($size >= 1024) {
        $size = $size / 1024;
        $tipo = ' KiB';
    }
    
    if ($size >= 2048) {
        $size = $size / 2048;
        $tipo = ' MiB';
    }
    $size = number_format($size, 1);
    echo '<label for="' . $status_tb['Name'] . '"><input type="checkbox" id="' . $status_tb['Name'] . '" name="selected_tbl[]" value="' . $status_tb['Name'] . '" checked="checked" />' . $status_tb['Name'] . '</label> - ' . $lang['dimensione'] . ': ' . $size . $tipo . ' ' . $eccesso . '<br />';
}

$sql_total_size = mysqli_query($db, "SELECT SUM(ROUND(((data_length + index_length) / 1024), 1)) AS Totale FROM information_schema.TABLES WHERE table_schema = '$db_name' and TABLE_NAME IN ('$tab_news', '$tab_utenti', '$tab_config', '$tab_livelli', '$tab_categorie', '$tab_commenti', '$tab_ban')");
$row_total_size = mysqli_fetch_assoc($sql_total_size); 
$tot_tipo = ($row_total_size['Totale'] <= 1024) ? ' KiB' : ' MiB';
echo ' &nbsp; &nbsp; ' . $lang['dimensione_totale'] . ': <b>' . $row_total_size['Totale'] . $tot_tipo . '</b><br />';
echo '<img src="' . $img_path . '/sel_all.gif" alt="select" /> ' . $lang['backup_compresso'] . ' <input type="radio" id="save" name="bck" value="save" /><label for="save">' . $lang['backup_save'] . ' ' . $file_dir . '</label> <input type="radio" id="download" name="bck" value="download" checked="checked" /><label for="download">' . $lang['backup_download'] . '</label> <input type="submit" name="backup" value="Backup" style="font-weight: bold;" /> ';
 
if( file_exists('../' . $file_dir . '/' . $bck_name . '.sql.gz')) { 
	echo '<a href="../' . $file_dir . '/' . $bck_name . '.sql.gz' . '">' . $bck_name . '.sql.gz' . '</a> (' . strftime('%d/%m/%Y, %H:%M', filemtime('../' . $file_dir . '/' . $bck_name . '.sql.gz')) . ' - KiB ' . round(filesize('../' . $file_dir . '/' . $bck_name . '.sql.gz') / 1024, 1) . ') <a href="impostazioni.php?action=bck_canc" onclick="return confirmSubmit();">' . $lang['backup_file_canc'] . '</a>';
}

echo '<br />';
?></td>                     
        </tr>                     
        <tr>                            
          <td bgcolor="#DEE3E7" align="center" colspan="2">
            <input type="submit" name="submit" value="<?php echo $lang['btn_modifica']; ?>" style="font-weight: bold;" />                           
            <input type="reset" name="reset" value="<?php echo $lang['btn_cancella']; ?>" /></td>                     
        </tr>                     
      </table>         
    </form><br />         
    <?php require_once ("footer.php"); mysqli_close($db); ?>   
  </body>
</html>