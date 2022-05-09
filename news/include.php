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

//includo i file di configurazione
require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/lang/' . $language . '.php');

//connessione a mysql
$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);
$config = @mysqli_query($db, "SELECT max_tit_include, max_parole_include, nuova_news_day, formato_data FROM `$tab_config`");
$config_val = @mysqli_fetch_assoc($config);

if ($config_val['max_parole_include'] > 0) {
    $result_inc = @mysqli_query($db, "SELECT nt.id, nt.titolo, LEFT(nt.testo, " . $config_val['max_parole_include'] . ") AS testo, nt.data_pubb, nca.nome_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nt.id_cat = nca.id_cat JOIN `$tab_utenti` nu ON nu.user_id = nt.user_id AND nt.data_pubb < " . time() . " AND nt.news_approvata = 1 ORDER BY nt.data_pubb DESC LIMIT " . $config_val['max_tit_include'] . "");

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
        'Google Map: \\4\\6',
        '\\1',
        '\\1',
        '\\2',
        '\\2'
    );
}
else {
    $result_inc = @mysqli_query($db, "SELECT nt.id, nt.titolo, nt.data_pubb, nca.nome_categoria FROM `$tab_news` nt JOIN `$tab_categorie` nca ON nt.id_cat = nca.id_cat JOIN `$tab_utenti` nu ON nu.user_id = nt.user_id AND nt.data_pubb < " . time() . " AND nt.news_approvata = 1 ORDER BY nt.data_pubb DESC LIMIT " . $config_val['max_tit_include'] . "");
}
echo '<table width="163" border="0" cellpadding="1" cellspacing="1">           
  <tr>                   
    <td align="center" bgcolor="#336699">' . $lang['ultime_notizie'] . '</td>           
  </tr>';

//stampo l'elenco delle news

while ($row_inc = @mysqli_fetch_array($result_inc)) {

    //seleziono il formato data
    
    switch ($config_val['formato_data']) {
        case 1:
            $data = strftime("%a %d %b %Y, %H:%M", $row_inc['data_pubb']);
        break;
        case 2:
            $data = str_replace("ì", "&igrave;", strftime("%A %d %B %Y, %H:%M", $row_inc['data_pubb']));
        break;
        case 3:
            $data = strftime("%d/%m/%Y, %H:%M", $row_inc['data_pubb']);
        break;
        case 4:
            $data = strftime("%d %b %Y, %H:%M", $row_inc['data_pubb']);
        break;
        case 5:
            $data = strftime("%d %B %Y, %H:%M", $row_inc['data_pubb']);
        break;
        case 6:
            $data = strftime("%m/%d/%Y, %I:%M %p", $row_inc['data_pubb']);
        break;
        case 7:
            $data = strftime("%B %d, %Y %I:%M %p", $row_inc['data_pubb']);
        break;
        case 8:
            $data = strftime("%I:%M %p %B %d, %Y", $row_inc['data_pubb']);
        break;
    }
    $img_new = ((time() - $row_inc['data_pubb']) <= 60 * 60 * 24 * $config_val['nuova_news_day']) ? '<img src="/' . $news_dir . '/' . $img_dir . '/new.gif" border="0" alt="" />' : '<img src="/' . $news_dir . '/' . $img_dir . '/art.gif" border="0" alt="" />';
    
    if ($config_val['max_parole_include'] > 0) {
        echo '<tr><td align="left" bgcolor="#FFFFFF">' . $img_new . ' <a href="/' . $news_dir . '/view.php?id=' . $row_inc['id'] . '" title="' . $row_inc['nome_categoria'] . '">' . $row_inc['titolo'] . '</a> ' . $data . '<br />' . preg_replace($testo_cerca, $testo_sostituisci, $row_inc['testo']) . '</td></tr>';
        echo "\n";
    }
    else {
        echo '<tr><td align="left" bgcolor="#FFFFFF">' . $img_new . ' <a href="/' . $news_dir . '/view.php?id=' . $row_inc['id'] . '" title="' . $row_inc['nome_categoria'] . '">' . $row_inc['titolo'] . '</a> ' . $data . '</td></tr>';
        echo "\n";
    }
}
echo '<tr><td align="center" bgcolor="#336699"><a href="/' . $news_dir . '/archivio.php" class="top">' . $lang['archivio_notizie'] . '</a></td></tr></table>';
?>