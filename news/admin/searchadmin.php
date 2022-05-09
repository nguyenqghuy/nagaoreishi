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

$conf = mysqli_query($db, "SELECT url_sito, max_ricerche, formato_data FROM `$tab_config`");
$rowconf = mysqli_fetch_array($conf);
$error = NULL;
$num_totale = NULL;
$rec_page = $rowconf['max_ricerche'];
$settimana = time() - 60 * 60 * 24 * 7;
$mese = time() - 60 * 60 * 24 * 31;
$anno = time() - 60 * 60 * 24 * 365;
$start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;
$campi_sost_vuoti = NULL;
$replace_ok = NULL;

if (isset($_GET['chiave'])) {
    $chiave = mysqli_real_escape_string($db, $_GET['chiave']);
    
    if (trim($chiave) == '' || strlen(trim($chiave)) < 4) {
        $error = '<br /><div id="error2">' . $lang['max_min_chars'] . '</div>';
        $doquery = NULL;
    }
    else {
        $error = NULL;
        $doquery = 1;
    }
}
else {
    $chiave = NULL;
    $doquery = NULL;
}

if (isset($_GET['rbw'])) {
    $rbw = $_GET['rbw'];
    
    switch ($rbw) {
        case 'in_news':
            $rb_in_news = 'checked="checked"';
            $rb_in_commenti = '';
        break;
        case 'in_commenti':
            $rb_in_news = '';
            $rb_in_commenti = 'checked="checked"';
        break;
        default:
            $rb_in_news = 'checked="checked"';
            $rb_in_commenti = '';
    }
}
else {
    $rb_in_news = 'checked="checked"';
    $rb_in_commenti = '';
}

if (isset($_GET['time'])) {
    $time = $_GET['time'];
    
    switch ($time) {
        case 'sett':
            $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm >= $settimana" : "nt.data_pubb >= $settimana";
            $q_field = NULL;
        break;
        case 'mese':
            $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm >= $mese" : "nt.data_pubb >= $mese";
            $q_field = NULL;
        break;
        case 'anno':
            $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm >= $anno" : "nt.data_pubb >= $anno";
            $q_field = NULL;
        break;
        case 'sempre':
            $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm > 1" : "nt.data_pubb > 1";
            $q_field = NULL;
        break;
        default:
            $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm >= $mese" : "nt.data_pubb >= $mese";
            $q_field = NULL;
    }
}
else {
    $q_time = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm >= $mese" : "nt.data_pubb >= $mese";
    $q_field = ", nt.letture";
    $time = "mese";
}

if (isset($_GET['ordine'])) {
    $ordine = $_GET['ordine'];
    
    switch ($ordine) {
        case 'titoli':
            $q_ordine = "nt.titolo ASC";
            $q_field = NULL;
        break;
        case 'datadesc':
            $q_ordine = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm DESC" : "nt.data_pubb DESC";
            $q_field = NULL;
        break;
        case 'piulette':
            $q_ordine = "nt.letture DESC";
            $q_field = ", letture";
        break;
        case 'pertinenza':
            $q_ordine = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nt.letture DESC" : "Pertinenza DESC";
            $q_field = NULL;
        break;
        case 'categoria':
            $q_ordine = "nca.nome_categoria ASC";
            $q_field = NULL;
        break;
        default:
            $q_ordine = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm DESC" : "nt.data_pubb DESC";
            $q_field = ", letture";
    }
}
else {
    $q_ordine = (isset($_GET['rbw']) && $_GET['rbw'] === 'in_commenti') ? "nc.data_comm DESC" : "nt.data_pubb DESC";
    $q_field = NULL;
    $ordine = "datadesc";
}

if (isset($_GET['autore'])) {
    $get_autore = intval($_GET['autore']);
    
    switch ($get_autore) {
        case 0:
            $q_autore = "nu.user_id > 0";
        break;
        default:
            $q_autore = "nu.user_id=$get_autore";
    }
}
else {
    $q_autore = "nu.user_id > 0";
    $get_autore = "0";
}

if (isset($_GET['categoria'])) {
    $get_categoria = intval($_GET['categoria']);
    
    switch ($get_categoria) {
        case 0:
            $q_categoria = "nt.id_cat > 0";
        break;
        default:
            $q_categoria = "nt.id_cat=$get_categoria";
    }
}
else {
    $q_categoria = "nt.id_cat > 0";
    $get_categoria = "0";
}

    $val_chiave = (isset($_GET['chiave'])) ? htmlspecialchars($_GET['chiave'], ENT_QUOTES, "ISO-8859-1") : NULL;

// Trova e sostituisci

if (isset($_POST['sostituzione'])) {
    
    if (trim($_POST['search_text']) == '' || trim($_POST['replace_text']) == '') {
        $campi_sost_vuoti = '<div id="error2">' . $lang['campi_sost_vuoti'] . '</div>';
    }
    else {
        $campi_sost_vuoti = NULL;
        
        if ($_SESSION['livello_id'] == 1) {
            $where_autore = ($_POST['autore'] == 0) ? ' user_id > 0' : ' user_id = ' . intval($_POST['autore']) . '';
        }
        else {
            $where_autore = " user_id = " . intval($_SESSION['user_id']) . "";
        }
        
        switch ($_POST['time']) {
            case 'sett':
                $where_data = "data_pubb >= $settimana";
            break;
            case 'mese':
                $where_data = "data_pubb >= $mese";
            break;
            case 'anno':
                $where_data = "data_pubb >= $anno";
            break;
            case 'sempre':
                $where_data = "data_pubb > 1";
            break;
            default:
                $where_data = "data_pubb >= $settimana";
        }
        $where_categoria = ($_POST['categoria'] == 0) ? ' id_cat > 0' : ' id_cat = ' . intval($_POST['categoria']) . '';
        
        $trova = mysqli_real_escape_string($db, htmlspecialchars($_POST['search_text'], ENT_QUOTES, "ISO-8859-1"));
        $sostituisci = mysqli_real_escape_string($db, htmlspecialchars($_POST['replace_text'], ENT_QUOTES, "ISO-8859-1"));
        
        switch ($_POST['dove']) {
            case 'titolo':
                $where_news = "titolo = REPLACE(titolo, '$trova', '$sostituisci')";
            break;
            case 'testo':
                $where_news = "testo = REPLACE(testo, '$trova', '$sostituisci')";
            break;
            case 'entrambi':
                $where_news = "titolo = REPLACE(titolo, '$trova', '$sostituisci'), testo = REPLACE(testo, '$trova', '$sostituisci')";
            break;
            default:
                $where_news = "titolo = REPLACE(titolo, '$trova', '$sostituisci')";
        }
        
        if (mysqli_query($db, "UPDATE `$tab_news` SET $where_news WHERE $where_data AND $where_autore AND $where_categoria")) {
            $replace_ok = '<div id="success">' . $lang['sostituzione_ok'] . ' ' . mysqli_affected_rows($db) . '</div>';
        }
        else {
            $replace_ok = '<div id="error2">' . $lang['sostituzione_errore'] . '</div>';
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">     
  <head>         
    <title><?php echo $lang['pagina_cerca']; ?>
    </title>         
    <link rel="stylesheet" href="../style.css" type="text/css" />		 
<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>      
  </head>     
  <body>
<?php
require_once ("menu.php");
?>         
    <div id="container-search" class="text">             
      <form name="cerca" id="cerca" method="get" action="searchadmin.php">                 
        <fieldset>                     
          <legend><b><?php echo $lang['cerca']; ?></b>                     
          </legend><br />          <?php echo $lang['cerca']; ?>                       
          <input type="text" size="14" name="chiave" maxlength="50" class="searchbox" value="<?php echo $val_chiave; ?>" /> <input type="radio" id="rb_news" name="rbw" value="in_news" <?php echo $rb_in_news; ?> /><label for="rb_news"><?php echo $lang['news']; ?></label> <input type="radio" id="rb_commenti" name="rbw" value="in_commenti" <?php echo $rb_in_commenti; ?> /><label for="rb_commenti"><?php echo $lang['commenti']; ?></label>
          <select name="time">                         
            <option value="sett"<?php echo (isset($_GET['time']) && $_GET['time'] == 'sett' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['settimana']; ?>             
            </option>                         
            <option value="mese"<?php echo (isset($_GET['time']) && $_GET['time'] == 'mese' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['mese']; ?>             
            </option>                         
            <option value="anno"<?php echo (isset($_GET['time']) && $_GET['time'] == 'anno' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['anno']; ?>             
            </option>                         
            <option value="sempre"<?php echo (isset($_GET['time']) && $_GET['time'] == 'sempre' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['sempre']; ?>             
            </option>                     
          </select> <?php echo $lang['scritte_da']; ?>
          <select name="autore">                           
            <option value="0"<?php echo (isset($_GET['autore']) && $_GET['autore'] == '0' ? ' selected="selected"' : NULL); ?>><?php echo $lang['da_tutti']; ?>
            </option>
<?php
$email_utente = ($_SESSION['livello_id'] == 1) ? 'nu.email,' : '';
$res_sel = mysqli_query($db, "SELECT nu.user_id, nu.nome_cognome, $email_utente (SELECT COUNT(nt.id) FROM `$tab_news` nt WHERE nt.user_id=nu.user_id) AS TotaleNews FROM `$tab_utenti` nu JOIN `$tab_news` nt ON nt.user_id=nu.user_id GROUP BY nu.user_id HAVING COUNT(nt.user_id)>0 ORDER BY nu.nome_cognome ASC");

//per evitare il Notice in caso di nessun autore presente
$autori = NULL;

if (mysqli_num_rows($res_sel) != 0) {
    $autori = array();
    
    while ($row_sel = mysqli_fetch_array($res_sel)) {
        $title_utente = ($_SESSION['livello_id'] == 1) ? $row_sel['email'] : 'ID: ' . $row_sel['user_id'];
        $utente = ($row_sel['user_id'] == $_SESSION['user_id']) ? $lang['tu'] : $row_sel['TotaleNews'];
        $autori[] = '<option value="' . $row_sel['user_id'] . '" title="' . $title_utente . '" ' . (isset($_GET['autore']) && $_GET['autore'] == $row_sel['user_id'] ? ' selected="selected"' : NULL) . '>' . $row_sel['nome_cognome'] . ' (' . $utente . ')</option>';
        echo "\n";
    }
    
    foreach ($autori as $autore) {
        echo $autore;
    }
}
?>                     
          </select>  in 
          <select name="categoria">            
            <option value="0"><?php echo $lang['da_tutti']; ?>
            </option>
<?php
$cat_sel = mysqli_query($db, "SELECT DISTINCT nca.id_cat, nca.nome_categoria FROM `$tab_categorie` nca, `$tab_news` nt WHERE (SELECT COUNT(nt.id_cat) FROM `$tab_news` nt WHERE nt.id_cat=nca.id_cat) > 0 ORDER BY nca.nome_categoria ASC");
$categorie = array();

while ($row_sel = mysqli_fetch_array($cat_sel)) {
    $categorie[] = '<option value="' . $row_sel['id_cat'] . '" ' . (isset($_GET['categoria']) && $_GET['categoria'] == $row_sel['id_cat'] ? ' selected="selected"' : NULL) . '>' . $row_sel['nome_categoria'] . '</option>';
    echo "\n";
}

foreach ($categorie as $categoria) {
    echo $categoria;
}
?>           
          </select> <?php echo $lang['ordina_per']; ?>
          <select name="ordine">                         
            <option value="pertinenza"<?php echo (isset($_GET['ordine']) && $_GET['ordine'] == 'pertinenza' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['pertinenza']; ?>             
            </option>                         
            <option value="datadesc"<?php echo (isset($_GET['ordine']) && $_GET['ordine'] == 'datadesc' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['piu_recenti']; ?>             
            </option>                         
            <option value="piulette"<?php echo (isset($_GET['ordine']) && $_GET['ordine'] == 'piulette' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['piu_lette']; ?>             
            </option>                         
            <option value="titoli"<?php echo (isset($_GET['ordine']) && $_GET['ordine'] == 'titoli' ? ' selected="selected"' : NULL); ?>>						             
            <?php echo $lang['titoli_az']; ?>             
            </option>            
            <option value="categoria"<?php echo (isset($_GET['ordine']) && $_GET['ordine'] == 'categoria' ? ' selected="selected"' : NULL); ?>>						
            <?php echo $lang['categoria_az']; ?>            
            </option>
          </select>            
          <input type="submit" name="submit" style="font-weight: bold;" value="<?php echo $lang['cerca']; ?>" /><br /><br />                 
        </fieldset>             
      </form><br /><br />

      <form name="sostituzione" id="sostituzione" method="post" action="searchadmin.php">   				
        <fieldset>                     
          <legend><b><?php echo $lang['sostituisci']; ?></b>                     
          </legend><br /><?php echo $lang['cerca']; ?>
          <input type="text" size="14" name="search_text" maxlength="50" class="searchbox" /> <?php echo $lang['sostituisci']; ?>
          <input type="text" size="14" name="replace_text" maxlength="50" class="replacebox" />            
          <select name="dove">                         
            <option value="titolo">in <?php echo $lang['titolo']; ?>
            </option>                         
            <option value="testo">in <?php echo $lang['testo']; ?>
            </option>                       
            <option value="entrambi">in <?php echo $lang['entrambi']; ?>
            </option>                     
          </select> <?php echo $lang['news']; ?>                       
          <select name="time">                         
            <option value="sett" <?php echo (isset($_GET['time']) && $_GET['time'] == 'sett' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['settimana']; ?>             
            </option>                         
            <option value="mese" <?php echo (isset($_GET['time']) && $_GET['time'] == 'mese' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['mese']; ?>             
            </option>                         
            <option value="anno" <?php echo (isset($_GET['time']) && $_GET['time'] == 'anno' ? ' selected="selected"' : NULL); ?>>                         
            <?php echo $lang['anno']; ?>             
            </option>                         
            <option value="sempre" <?php echo (isset($_GET['time']) && $_GET['time'] == 'sempre' ? ' selected="selected"' : NULL); ?>>                       
            <?php echo $lang['sempre']; ?>             
            </option>                     
          </select>                             
<?php

if ($_SESSION['livello_id'] == 1) {
    echo $lang['scritte_da'] . ' <select name="autore">
				<option value="0">' . $lang['da_tutti'] . '</option>';

    //stampo gli autori solo se esistono
    
    if (is_array($autori)) {
        
        foreach ($autori as $autore) {
            echo $autore;
        }
    }
    echo '</select>';
}
?> in 
          <select name="categoria">            
            <option value="0"><?php echo $lang['da_tutti']; ?>
            </option>
<?php

foreach ($categorie as $categoria) {
    echo $categoria;
}
?>           
          </select>          
          <input type="submit" name="sostituzione" style="font-weight: bold;" value="<?php echo $lang['sostituisci']; ?>" /><br /><br />                 
        </fieldset>      
      </form>   <br />
<?php
echo $campi_sost_vuoti . $replace_ok;

if ($doquery == 1) {

    if (isset($_GET['rbw']) AND $_GET['rbw'] == 'in_news') {

        //Ricerca news

        $result = mysqli_query($db, "SELECT nt.id, nt.titolo, nu.user_id, nca.nome_categoria, nu.nome_cognome, nt.data_pubb$q_field, MATCH(nt.titolo, nt.testo, nt.immagine) AGAINST ('$chiave*' IN BOOLEAN MODE) AS Pertinenza 
	FROM `$tab_news` nt
	JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id
	JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat
	WHERE MATCH (nt.titolo, nt.testo, nt.immagine) AGAINST ('$chiave*' IN BOOLEAN MODE)
	AND $q_time AND $q_autore AND $q_categoria ORDER BY $q_ordine LIMIT $start, $rec_page");
        $sql_num_totale = mysqli_query($db, "SELECT COUNT(nt.id) AS NumTotale FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id WHERE MATCH (nt.titolo, nt.testo, nt.immagine) AGAINST ('$chiave*' IN BOOLEAN MODE) AND $q_time AND $q_autore AND $q_categoria");
		$num_totale_riga = mysqli_fetch_array($sql_num_totale);
		$num_totale = $num_totale_riga['NumTotale'];

        //se la ricerca non produce risultati stampo l'avviso
        $trovata = ($num_totale == 0) ? '<br /><div id="error2">' . $lang['no_results'] . '<br /><a href="https://www.google.com/search?q=' . stripslashes(htmlspecialchars($chiave, ENT_QUOTES, "ISO-8859-1")) . '&amp;sitesearch=' . $rowconf['url_sito'] . '/' . $news_dir . '" title="Google" class="piccolo" target="_blank">' . $lang['no_results_google'] . '</a></div>' : '<span class="text"><b>' . $num_totale . '</b> ' . $lang['risultati'] . ' <b>' . stripslashes(htmlspecialchars($chiave, ENT_QUOTES, "ISO-8859-1")) . '</b></span><br />';
        echo $trovata;
        
        while ($row = mysqli_fetch_array($result)) {

            //seleziono il formato data
            
            switch ($rowconf['formato_data']) {
                case 1:
                    $data = strftime("%a %d %b %Y, %H:%M", $row['data_pubb']);
                break;
                case 2:
                    $data = str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M", $row['data_pubb']));
                break;
                case 3:
                    $data = strftime("%d/%m/%Y, %H:%M", $row['data_pubb']);
                break;
                case 4:
                    $data = strftime("%d %b %Y, %H:%M", $row['data_pubb']);
                break;
                case 5:
                    $data = strftime("%d %B %Y, %H:%M", $row['data_pubb']);
                break;
                case 6:
                    $data = strftime("%m/%d/%Y, %I:%M %p", $row['data_pubb']);
                break;
                case 7:
                    $data = strftime("%B %d, %Y %I:%M %p", $row['data_pubb']);
                break;
                case 8:
                    $data = strftime("%I:%M %p %B %d, %Y", $row['data_pubb']);
                break;
            }

            //stampo i risultati della ricerca
            $row['titolo'] = str_ireplace($chiave, "<b>" . $chiave . "</b>", $row['titolo']);
            $row['letture'] = (isset($row['letture'])) ? '' . number_format($row['letture'], 0, '', '.') . ' ' . $lang['letture'] . ' - ' : NULL;
            $modifica_news = ($row['user_id'] == $_SESSION['user_id'] || $_SESSION['livello_id'] == 1) ? ' - <a href="modifica.php?id=' . $row['id'] . '" target="_blank">' . $lang['modifica'] . ' <img src="' . $img_path . '/nw.gif" alt="" title="" border="0"/></a>' : NULL;
            echo '<img src="' . $img_path . '/news.png" alt="" /> ' . $row['letture'] . ' ' . str_replace($chiave, "<b>" . $chiave . "</b>", $row['titolo']) . ' (' . $data . ' - ' . $row['nome_cognome'] . ') [' . $lang['pertinenza'] . ': ' . $row['Pertinenza'] . ' - ' . $lang['categoria'] . ': ' . $row['nome_categoria'] . '] ' . $modifica_news . '<br />';
        }
    }
    else {

        //Ricerca commenti
        $result = mysqli_query($db, "SELECT nt.id, nu.user_id, nc.id_comm, nc.data_comm FROM `$tab_commenti` nc
JOIN `$tab_news` nt ON nt.id = nc.id_news
JOIN `$tab_utenti` nu ON nu.user_id = nt.user_id
JOIN `$tab_categorie` nca ON nca.id_cat = nt.id_cat
WHERE MATCH (nc.commento) AGAINST ('$chiave*' IN BOOLEAN MODE) AND $q_time AND $q_autore AND $q_categoria ORDER BY $q_ordine LIMIT $start, $rec_page");
        $sql_num_totale = mysqli_query($db, "SELECT COUNT(nc.id_comm) AS NumTotale FROM `$tab_commenti` nc JOIN `$tab_news` nt ON nt.id = nc.id_news JOIN `$tab_utenti` nu ON nu.user_id = nt.user_id JOIN `$tab_categorie` nca ON nca.id_cat = nt.id_cat WHERE MATCH (nc.commento) AGAINST ('$chiave*' IN BOOLEAN MODE) AND $q_time AND $q_autore AND $q_categoria");
		$num_totale_riga = mysqli_fetch_array($sql_num_totale);
		$num_totale = $num_totale_riga['NumTotale'];

        //se la ricerca non produce risultati stampo l'avviso
        $trovata = ($num_totale == 0) ? '<br /><div id="error2">' . $lang['no_results'] . '<br /><a href="https://www.google.com/search?q=' . stripslashes(htmlspecialchars($chiave, ENT_QUOTES, "ISO-8859-1")) . '&amp;sitesearch=' . $rowconf['url_sito'] . '/' . $news_dir . '" title="Google" class="piccolo" target="_blank">' . $lang['no_results_google'] . '</a></div>' : '<span class="text"><b>' . $num_totale . '</b> ' . $lang['commenti_trovati'] . ' <b>' . stripslashes(htmlspecialchars($chiave, ENT_QUOTES, "ISO-8859-1")) . '</b></span><br />';
        echo $trovata;
        
        while ($row = mysqli_fetch_array($result)) {

            //seleziono il formato data
            
            switch ($rowconf['formato_data']) {
                case 1:
                    $data = strftime("%a %d %b %Y, %H:%M", $row['data_comm']);
                break;
                case 2:
                    $data = str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M", $row['data_comm']));
                break;
                case 3:
                    $data = strftime("%d/%m/%Y, %H:%M", $row['data_comm']);
                break;
                case 4:
                    $data = strftime("%d %b %Y, %H:%M", $row['data_comm']);
                break;
                case 5:
                    $data = strftime("%d %B %Y, %H:%M", $row['data_comm']);
                break;
                case 6:
                    $data = strftime("%m/%d/%Y, %I:%M %p", $row['data_comm']);
                break;
                case 7:
                    $data = strftime("%B %d, %Y %I:%M %p", $row['data_comm']);
                break;
                case 8:
                    $data = strftime("%I:%M %p %B %d, %Y", $row['data_comm']);
                break;
            }

            //stampo i risultati della ricerca
            
            if ($_SESSION['livello_id'] == 1) {
                echo '<img src="' . $img_path . '/comm.png" alt="" /> ID ' . $lang['commento'] . ' ' . $row['id_comm'] . ' - ' . $data . ' <a href="commenti.php?id_news=' . $row['id'] . '" target="_blank">' . $lang['news'] . ' <img src="' . $img_path . '/nw.gif" alt="" title="" border="0"/></a> - <a href="javascript:;" onclick="window.open(\'modifica_commento.php?modo=sa&amp;id_comm=' . $row['id_comm'] . '\', \'\', \'width=470, height=350, resizable=1, scrollbars=1, location=1, status=1\');" title="[Popup]">' . $lang['modifica'] . '</a><br />';
            }
            else {
                echo '<img src="' . $img_path . '/comm.png" alt="" /> ID ' . $lang['commento'] . ' ' . $row['id_comm'] . ' - ' . $data . ' - <a href="../view.php?id=' . $row['id'] . '" target="_blank">' . $lang['news'] . ' <img src="' . $img_path . '/nw.gif" alt="" title="" border="0"/></a><br />';
            }
        }
    }
}
echo $error . '<br />';

if ($num_totale > $rec_page) {
    echo '<div id="paginazione" class="text2">';

    //paginazione
    $numero_pagine = ceil($num_totale / $rec_page);
    $pagina_attuale = ceil(($start / $rec_page) + 1);
    echo page_bar("searchadmin.php?chiave=" . stripslashes(htmlspecialchars($chiave, ENT_QUOTES, "ISO-8859-1")) . "&amp;rbw=$rbw&amp;categoria=$get_categoria&amp;time=$time&amp;ordine=$ordine&amp;autore=$get_autore", $pagina_attuale, $numero_pagine, $rec_page);
    echo '</div><br/><br />';
}


?>         
    </div><br />         
    <?php require_once ("footer.php");mysqli_close($db);  ?>      
  </body>
</html>