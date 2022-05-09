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
header('Content-type: text/html; charset=ISO-8859-1');

//includo i file di configurazione
require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/pdf/fpdf.php');
require_once (dirname(__FILE__) . '/lang/' . $language . '.php');

//connessione a mysql
$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);
$get_id = (isset($_GET['id']) && preg_match('/^[0-9]{1,8}$/', $_GET['id'])) ? intval($_GET['id']) : 0;
$sql_news = @mysqli_query($db, "SELECT nt.id, nt.titolo, nt.testo, nt.data_pubb, nu.nome_cognome, nca.nome_categoria FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nu.user_id=nt.user_id JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.id=$get_id AND nt.news_approvata = 1 AND nt.data_pubb < " . time() . "");
$rownews = @mysqli_fetch_array($sql_news);

if (@mysqli_num_rows($sql_news) > 0) {
    $titolo = html_entity_decode($rownews['titolo'], ENT_QUOTES, 'ISO-8859-1');
    $testo = nl2br(html_entity_decode($rownews['testo'], ENT_QUOTES, 'ISO-8859-1'));
    $autore = html_entity_decode($rownews['nome_cognome'], ENT_QUOTES, 'ISO-8859-1');

    //estraggo alcune impostazioni
    $sql_conf = @mysqli_query($db, "SELECT url_sito, formato_data FROM `$tab_config`");
    $rowconf = @mysqli_fetch_array($sql_conf);
    $url_sito = $rowconf['url_sito'];

    //seleziono il formato data
    
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

    //sostituisco i codici di formattazione
    $testo_cerca = array(
        '{&rsquo;}',
        '{&euro;}',
        '{<br />}',
        '{\[ul]}',
        '{\[li]}',
        '{\[/li]}',
        '{\[/ul]}',
        '{&amp;}',
        '{\[img]}',
        '{\[/img]}',
        '{\[b]}',
        '{\[i]}',
        '{\[u]}',
        '{\[/b]}',
        '{\[/i]}',
        '{\[/u]}',
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
        '\'',
        'E',
        '',
        '',
        "\n#",
        '',
        '',
        '&',
        '[' . $lang['immagine'] . ': ',
        ']',
        '',
        '',
        '',
        '',
        '',
        '',
        '\\2',
        '\\2 [mailto: \\1]',
        '\\4\\6',
        '\\9',
        '\\4\\6',
        '\\9 [\\5\\7]',
        '\\7',
		'\\7',
        "[" . $lang['citazione'] . "]\n\\2\n[/" . $lang['citazione'] . "]",
        "[" . $lang['codice'] . "]\n\\2\n[/" . $lang['codice'] . "]",
        '[Video: youtube.com/watch?v=\\1]',
        'Google Map: \\4\\6',
        'ICQ: \\1',
        'Skype: \\1',
        'AIM: \\2',
        'Yahoo! Messenger: \\2'
    );
    $testoPdf = preg_replace($testo_cerca, $testo_sostituisci, $testo);
    
    class PDF extends FPDF {
        
        function ChapterTitle() {
            global $titolo, $autore, $url_sito, $news_dir, $rownews, $img_dir, $data;
            $titoloChars = array(
                '&rsquo;' => '\'',
                '&euro;' => 'E'
            );
            $titoloPdf = strtr($titolo, $titoloChars);

            //inserire il logo del proprio sito
            $this->Image($img_dir . '/logonews.gif', 11, 8, 38, 7);
            $this->Ln(8);

            //font e posizione della cella contenente il titolo
            $this->SetFont('Times', 'B', 9);
            $this->Cell(1);

            //posizione del titolo
            $this->MultiCell(0, 4, $titoloPdf, 0);
            $this->Ln(5);

            //font e posizione delle info sulla notizia
            $this->SetFont('Times', '', 9);
            $this->SetFillColor(225, 225, 225);
            $this->Cell(0, 4, "$autore - $data - " . $rownews['nome_categoria'] . " - $url_sito/$news_dir/view.php?id=" . $rownews['id'] . "", 0, 1, "L", 1);
            $this->Ln(8);

            //metadati
            $this->Settitle($titoloPdf);
            $this->SetAuthor($autore);
        }
        
        function PrintChapter() {
            $this->ChapterTitle();
        }

        //footer
        
        function Footer() {
            global $lang;

            //Paginazione a 15 mm dal bordo inferiore della pagina
            $this->SetY(-15);
            $this->SetTextColor(120, 120, 120);
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 10, $lang['pagina'] . ' ' . $this->PageNo() . ' ' . $lang['di'] . ' {nb}', 0, 0, 'C');
        }
    }

    //istanzio la classe e invio il pdf al browser
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->PrintChapter();
    $pdf->MultiCell(0, 4, $testoPdf, 0);
    $pdf->Output("News_" . $rownews['id'] . ".pdf", 'D'); //Nome del file; D=scarica il file; I=apre il file nel browser

    mysqli_close($db);
}
else {
    die("No news");
}
?>