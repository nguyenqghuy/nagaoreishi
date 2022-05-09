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

//server MySQL
$db_host = 'nguyenqghuy.domaincommysql.com'; 

//utente del database
$db_user = 'nagaoacc';

//password per il database
$db_password = 'huyquang'; 

//nome del database
$db_name = 'nagao';

//nomi delle tabelle
$tab_news = 'news_testi';
$tab_utenti = 'news_utenti';
$tab_config = 'news_config';
$tab_livelli = 'news_livelli';
$tab_categorie = 'news_categorie';
$tab_commenti = 'news_commenti';
$tab_ban = 'news_ban';

//directory di installazione (senza slash iniziale o finale). Esempio: 'news' o 'directory/news'
$news_dir = 'news';

//directory delle immagini (senza slash iniziale o finale)
$img_dir = 'images';

//directory di upload file (senza slash iniziale o finale): deve avere permessi in scrittura
$file_dir = 'upload';

//localizzazione applicazione: ita, eng, fra, esp, deu
$language = 'eng';

//localizzazione data: decommentare quella desiderata 
//setlocale(LC_TIME, 'it_IT.UTF-8', 'it', 'ita');
setlocale(LC_TIME, 'en_EN.UTF-8', 'en', 'eng');
//setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr', 'fra');   
//setlocale(LC_TIME, 'es_ES.UTF-8', 'es', 'esp');
//setlocale(LC_TIME, 'de_DE.UTF-8', 'de', 'deu');

//percorso dalla root del sito alla directory delle immagini - NON MODIFICARE
$img_path = '/' . $news_dir . '/' . $img_dir;

//percorso alla directory di upload - NON MODIFICARE
$upload_path = $news_dir . '/' . $file_dir;

//percorso assoluto alla directory amministrazione (senza slash finale) - NON MODIFICARE
$dir_admin = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $news_dir . '/admin';

//versione attuale dello script - NON MODIFICARE
$version = '1.2.3';
//lENGHT of random code
$random_string_length = 10;
//Group of letter to create random code
$characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
?>