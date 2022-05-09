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

// se sono un amministratore e voglio visualizzare questa pagina, redirigo alla gestione news

if ($_SESSION['livello_id'] == 1) {
    header('Location: ' . $dir_admin . '/gestione_news.php');
    exit();
}

// risultati visualizzati per pagina
$conf = mysqli_query($db, "SELECT max_news_personali, formato_data FROM `$tab_config`");
$rowconf = mysqli_fetch_array($conf);
$rec_page = $rowconf['max_news_personali'];
$query_msg = NULL;

//serve per la paginazione
$start = (isset($_GET['start'])) ? abs(floor(intval($_GET['start']))) : 0;
$q_user_id = intval($_SESSION['user_id']);

//se ho ordinato le news in base a....

if (isset($_GET['sortby'])) {
    $get_sortby = "sortby=" . addslashes($_GET['sortby']);
    
    switch ($_GET['sortby']) {
        case 'titolo_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.titolo ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a> <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'titolo_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.titolo DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_asc&amp;start=' . $start . '">' . $lang['titolo'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'data_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.data_pubb ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a> <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'data_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.data_pubb DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
			$link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';
        break;
        case 'letture_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.letture ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a> <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'letture_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.letture DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_asc&amp;start=' . $start . '">' . $lang['letture'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'cat_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nca.nome_categoria, nca.img_categoria ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>  <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'cat_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nca.nome_categoria, nca.img_categoria DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_asc&amp;start=' . $start . '">' . $lang['categoria'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'comm_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY TotaleCommenti ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a> <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
        case 'comm_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY TotaleCommenti DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_asc&amp;start=' . $start . '">' . $lang['commenti'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
        break;
		case 'approvata_asc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.news_approvata ASC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a> <img src="' . $img_path . '/asc.gif" alt="ASC" title="ASC" />';            
        break;
        case 'approvata_desc':
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.news_approvata DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_desc&amp;start=' . $start . '">' . $lang['data'] . '</a>';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_desc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_asc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';        
        break;
        default:
            $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.data_pubb DESC LIMIT $start,$rec_page";
            $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
            $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
            $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
            $link_commenti = '<a href="elenco_news.php?sortby=comm_asc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
            $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
            $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';            
    }
}
else {
    $get_sortby = NULL;
    $order_query = "SELECT nt.id, LEFT(nt.titolo, 100) AS titolo, nt.data_pubb, nt.letture, nt.abilita_commenti, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=nt.id) AS TotaleCommenti, nca.nome_categoria, nca.img_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.user_id=$q_user_id ORDER BY nt.data_pubb DESC LIMIT $start,$rec_page";
    $link_titolo = '<a href="elenco_news.php?sortby=titolo_desc&amp;start=' . $start . '">' . $lang['titolo'] . '</a>';
    $link_data = '<a href="elenco_news.php?sortby=data_asc&amp;start=' . $start . '">' . $lang['data'] . '</a> <img src="' . $img_path . '/desc.gif" alt="DESC" title="DESC" />';
    $link_letture = '<a href="elenco_news.php?sortby=letture_desc&amp;start=' . $start . '">' . $lang['letture'] . '</a>';
    $link_commenti = '<a href="elenco_news.php?sortby=comm_asc&amp;start=' . $start . '">' . $lang['commenti'] . '</a>';
    $link_categorie = '<a href="elenco_news.php?sortby=cat_desc&amp;start=' . $start . '">' . $lang['categoria'] . '</a>';
    $link_approvata = '<a href="elenco_news.php?sortby=approvata_desc&amp;start=' . $start . '">' . $lang['news_approvata'] . '</a>';                
}

//cancellazione news e immagini

if (isset($_POST['submit_sel']) && !isset($_POST['submit_cat'])) {
    
    if (isset($_POST['cb_id'])) {
        $nid = implode(",", $_POST['cb_id']);

        //cancello le news
        
        if ($_POST['submit_sel'] == 'cancella_news') {
            
            if (mysqli_query($db, "DELETE FROM `$tab_news` WHERE user_id=$q_user_id AND id IN ($nid)")) {
                $query_msg = '<div id="success">' . $lang['canc_news_user_ok'] . '</div><br />';
            }
            else {
                $query_msg = '<div id="error">' . $lang['canc_news_user_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
            }
        }

        // abilito i commenti per le news
        
        if ($_POST['submit_sel'] == 'abilita_comm') {
            
            if (mysqli_query($db, "UPDATE `$tab_news` SET abilita_commenti=1 WHERE abilita_commenti=0 AND id IN ($nid) AND user_id=$q_user_id")) {
                $query_msg = '<div id="success">' . $lang['abilita_commenti_ok'] . '</div><br />';
            }
            else {
                $query_msg = '<div id="error">' . $lang['abilita_commenti_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
            }
        }

        // disabilito i commenti per le news
        
        if ($_POST['submit_sel'] == 'disabilita_comm') {
            
            if (mysqli_query($db, "UPDATE `$tab_news` SET abilita_commenti=0 WHERE abilita_commenti=1 AND id IN ($nid) AND user_id=$q_user_id")) {
                $query_msg = '<div id="success">' . $lang['disabilita_commenti_ok'] . '</div><br />';
            }
            else {
                $query_msg = '<div id="error">' . $lang['disabilita_commenti_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
            }
        }

    }
    else {
        $nid = NULL;
        $query_msg = '<div id="error">' . $lang['selez_news_error'] . '</div><br />';
    }
}

//cambio categoria
elseif (isset($_POST['submit_cat'])) {
    
    if (isset($_POST['cb_id'])) {
        $nid = implode(",", $_POST['cb_id']);
        
        if ($_POST['categoria'] == 'scegli') {
            $query_msg = '<div id="error">' . $lang['news_nuova_categoria_errore'] . '</div><br />';
        }
        else {
            
            if (mysqli_query($db, "UPDATE `$tab_news` SET id_cat=" . intval($_POST['categoria']) . " WHERE id IN ($nid) AND user_id=$q_user_id")) {
                $query_msg = '<div id="success">' . $lang['news_nuova_categoria_ok'] . '</div><br />';
            }
            else {
                $query_msg = '<div id="error">' . $lang['news_nuova_categoria_errore'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
            }
        }
    }
    else {
        $nid = NULL;
        $query_msg = '<div id="error">' . $lang['selez_news_error'] . '</div><br />';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">     
  <head>         
    <title><?php echo $lang['gestione_news']; ?>     
    </title>         
    <link rel="stylesheet" href="../style.css" type="text/css" />		 
<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>      
  </head>     
  <body>
<?php
require_once ("menu.php");
echo $query_msg;
echo '<form name="admin" action="elenco_news.php" method="post">
<table width="100%" style="border: 3px solid #DDDDDD;" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF" align="center">
<tr><td width="2%" bgcolor="#EEEEEE"></td>
<td width="35%" class="text" align="center" bgcolor="#EEEEEE">' . $link_titolo . '</td>
<td width="19%" class="text" align="center" bgcolor="#EEEEEE">' . $link_data . '</td>
<td width="9%" class="text" align="center" bgcolor="#EEEEEE">' . $link_letture . '</td>
<td width="10%" class="text" align="center" bgcolor="#EEEEEE">' . $link_commenti . '</td>
<td width="10%" class="text" align="center" bgcolor="#EEEEEE">' . $link_categorie . '</td>
<td width="6%" class="text" align="center" bgcolor="#EEEEEE">' . $link_approvata . '</td>
<td width="12%" class="text" align="center" bgcolor="#EEEEEE">' . $lang['opzioni'] . '</td>
</tr>';
$q_order = mysqli_query($db, "$order_query");

while ($q_riga = mysqli_fetch_array($q_order)) {

    //seleziono il formato data
    
    switch ($rowconf['formato_data']) {
        case 1:
            $data = strftime("%a %d %b %Y, %H:%M", $q_riga['data_pubb']);
        break;
        case 2:
            $data = str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M", $q_riga['data_pubb']));
        break;
        case 3:
            $data = strftime("%d/%m/%Y, %H:%M", $q_riga['data_pubb']);
        break;
        case 4:
            $data = strftime("%d %b %Y, %H:%M", $q_riga['data_pubb']);
        break;
        case 5:
            $data = strftime("%d %B %Y, %H:%M", $q_riga['data_pubb']);
        break;
        case 6:
            $data = strftime("%m/%d/%Y, %I:%M %p", $q_riga['data_pubb']);
        break;
        case 7:
            $data = strftime("%B %d, %Y %I:%M %p", $q_riga['data_pubb']);
        break;
        case 8:
            $data = strftime("%I:%M %p %B %d, %Y", $q_riga['data_pubb']);
        break;
    }
    $comm_abilitati = ($q_riga['abilita_commenti'] == 1) ? '' : ' <img src="' . $img_path . '/no_comm.png" title="' . $lang['comm_disab_icon'] . '" alt="" />';
    $link_leggi = ($q_riga['data_pubb'] < time() && $q_riga['news_approvata'] == 1) ? '<a href="../view.php?id=' . $q_riga['id'] . '" target="_blank">' . $lang['leggi'] . '</a>' : '<span style="color: #AAAAAA">' . $lang['leggi'] . '</span>';
    $icona_data_futura = ($q_riga['data_pubb'] > time()) ? '<img src="' . $img_path . '/clock.png" alt="data" />' : '';
    $news_approvata = ($q_riga['news_approvata'] == 1) ? $lang['si'] : '<span style="color: #FF0000"><b>' . $lang['no'] . '</b></span>';    
    echo '<tr onmouseover="this.bgColor=\'#E6F1FA\'" onmouseout="this.bgColor=\'#FFFFFF\'">
                      <td align="center"><input type="checkbox" name="cb_id[]" value="' . $q_riga['id'] . '" id="news_' . $q_riga['id'] . '" /></td>
                      <td align="left" class="text"><label for="news_' . $q_riga['id'] . '">' . $q_riga['titolo'] . '</label></td>
                      <td align="left" class="text">' . $icona_data_futura . ' ' . $data . '</td>
                      <td align="center" class="text">' . number_format($q_riga['letture'], 0, '', '.') . '</td>
                      <td align="center" class="text">' . $q_riga['TotaleCommenti'] . ' ' . $comm_abilitati . '</td>
                      <td align="left" class="text"><img src="' . $q_riga['img_categoria'] . '" width="16" height="16" alt="" /> ' . $q_riga['nome_categoria'] . '</td>
                      <td align="center" class="text">' . $news_approvata . ' </td>                      
                      <td align="center" class="text"><a href="modifica.php?id=' . $q_riga['id'] . '">' . $lang['modifica'] . '</a>&bull;' . $link_leggi . '</td>
                      </tr>';
}
echo '<tr>
  <td colspan="4" bgcolor="#EEEEEE" class="text2" align="left">
' . $lang['select'] . ' <a href="javascript:onClick=checkTutti()" class="piccolo">' . $lang['select_all'] . '</a>, <a href="javascript:onclick=uncheckTutti()" class="piccolo">' . $lang['select_none'] . '</a>&nbsp;
<select name="submit_sel" onchange="return dropdown(this);">
    <option selected="selected">' . $lang['operazioni'] . '</option>
    <option value="cancella_news" style="background:red; color:white;">' . $lang['cancella_news'] . '</option>
    <option value="abilita_comm">' . $lang['commenti_on'] . '</option>
    <option value="disabilita_comm">' . $lang['commenti_off'] . '</option>
</select> 
' . $lang['sposta_news'] . ' 
<select name="categoria">
<option value="scegli" selected="selected">' . $lang['scegli'] . '</option>';
$cat_sel = mysqli_query($db, "SELECT id_cat, nome_categoria FROM `$tab_categorie` ORDER BY nome_categoria ASC");

while ($row_sel = mysqli_fetch_array($cat_sel)) {
    echo '<option value="' . $row_sel['id_cat'] . '">' . $row_sel['nome_categoria'] . '</option>';
    echo "\n";
}
echo '</select> <input type="submit" name="submit_cat" value="' . $lang['vai'] . '" onclick="return confirmSubmit();" style="font-weight: bold;" /></td>';
echo '<td colspan="4" bgcolor="#EEEEEE" class="text2" align="right">';

//paginazione
$sql_num_totale = mysqli_query($db, "SELECT COUNT(id) AS NumTotale FROM `$tab_news` WHERE user_id=$q_user_id");
$num_totale_riga = mysqli_fetch_array($sql_num_totale);
$numero_pagine = ceil($num_totale_riga['NumTotale'] / $rec_page);
$pagina_attuale = ceil(($start / $rec_page) + 1);
echo '<b>(' . $lang['totale'] . ' ' . $num_totale_riga['NumTotale'] . ')</b> ' . page_bar("elenco_news.php?$get_sortby", $pagina_attuale, $numero_pagine, $rec_page);
echo '</td></tr></table>';
?>         
    </form><br />         
    <?php require_once ("footer.php"); ?>      
  </body>
</html>