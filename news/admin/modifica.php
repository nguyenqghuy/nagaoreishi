<?php

session_start();
header('Content-type: text/html; charset=utf-8');

//calcolo il tempo di generazione della pagina (1a parte)
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];

//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/functions.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');
require_once (dirname(__FILE__) . '/../../php/constant.php');

$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);
check_login();

//la pagina edit deve essere richiamata solo con id via get

if (isset($_GET['id']) && preg_match('/^[0-9]{1,8}$/', $_GET['id'])) {
    $idnews = intval($_GET['id']);
}
else {
    header('Location: ' . $dir_admin . '/gestione_news.php');
    exit();
}
$view_art = mysqli_query($db, "SELECT nt.id, nt.titolo, nt.description, nt.friendly_url, nt.shortdescription, nt.testo, nt.id_cat, nt.data_pubb, nt.letture, nt.immagine, nt.nosmile, nt.news_approvata, (SELECT COUNT(nco.id_news) FROM `$tab_commenti` nco WHERE nco.id_news=$idnews) AS TotaleCommenti, nt.abilita_commenti, nt.notifica_commenti, INET_NTOA(nt.ip) AS ip, nu.user_id, nu.nome_cognome, nu.permessi, nca.nome_categoria, (SELECT formato_data FROM `$tab_config`) AS FormatoData FROM `$tab_news` nt JOIN `$tab_utenti` nu ON nt.user_id = nu.user_id JOIN `$tab_categorie` nca ON nca.id_cat=nt.id_cat WHERE nt.id=$idnews");
$q_riga = mysqli_fetch_assoc($view_art);

//se NON sono un amministratore e voglio modificare le news di un altro utente, redirigo all'elenco news

if ($q_riga['user_id'] != $_SESSION['user_id'] && $_SESSION['livello_id'] != 1) {
    header('Location: ' . $dir_admin . '/elenco_news.php');
    exit();
}

//se l'ID dell'articolo selezionato non esiste in tabella

if (mysqli_num_rows($view_art) == 0) {
    die("No news");
}
$titolo_value = html_entity_decode($q_riga['titolo'], ENT_QUOTES, "ISO-8859-1");
$testo_value = html_entity_decode($q_riga['testo'], ENT_QUOTES, "ISO-8859-1");
$description = $q_riga['description'];
$shortdescription = $q_riga['shortdescription'];
$friendly_url = $q_riga['friendly_url'];
$autore_value = ($q_riga['user_id'] == NULL) ? $q_riga['nome_cognome'] : '<a href="profilo_admin.php?user_id=' . $q_riga['user_id'] . '" class="piccolo" target="_blank">' . $q_riga['nome_cognome'] . ' <img src="' . $img_path . '/nw.gif" border="0" alt="" title="" /></a>';
$letture = (isset($_POST['letture'])) ? intval($_POST['letture']) : $q_riga['letture'];
$immagine = ($q_riga['immagine'] == '') ? NULL : $q_riga['immagine'];
$nosmile_checked = ($q_riga['nosmile'] == 0) ? NULL : 'checked="checked"';
$commenti_checked = ($q_riga['abilita_commenti'] == 0) ? NULL : 'checked="checked"';
$notifica_commenti_checked = ($q_riga['notifica_commenti'] == 0) ? NULL : 'checked="checked"';
$ip = $q_riga['ip'];
$link_autore = ($_SESSION['livello_id'] == 1) ? '<b>' . $lang['autore'] . '</b> ' . $autore_value . '' : NULL;
$div_preview = NULL;
$data_pubb_value = strftime("%d/%m/%Y %H:%M", $q_riga['data_pubb']);
$approva_checked = ($q_riga['news_approvata'] == 1) ? 'checked="checked"' : NULL;


if ($q_riga['data_pubb'] > time()) {
	$data_pubb_disabled = NULL;
	$data_futura_checked = 'checked="checked"';
} else {
	$data_pubb_disabled = 'disabled="disabled"';
	$data_futura_checked = NULL;
}

//seleziono il formato data
  $data = GetDateStr($q_riga['FormatoData'], $q_riga);
$upload_msg = NULL;
$insert_empty = NULL;
$insert_msg_ok = NULL;
$deleteok = NULL;
$q_user = mysqli_query($db, "SELECT autorizza_news, permessi, im_num FROM `$tab_utenti` WHERE user_id=" . $_SESSION['user_id']);
$q_riga_perm = mysqli_fetch_assoc($q_user);
$im_num = ($q_riga_perm['im_num'] == '') ? 'USERNAME' : $q_riga_perm['im_num'];

//se ho cliccato sul bottone Anteprima
$nosmile = 0;
if (isset($_POST['preview'])) {
    immagine_apertura();
    //sostituzione();
	
	if (isset($_POST['nosmile'])) {
		$nosmile = 1;	
	}else{
		$nosmile = 0;
	}
	
    $testo = bbCode($_POST['testo'], $nosmile);
	$news_conf = mysqli_query($db, "SELECT larghezza, sfondo_notizia FROM `$tab_config`");
	$val_news_conf = mysqli_fetch_assoc($news_conf);
	$img_view = ($immagine != '') ? '<div class="imgap"><img src="' . $immagine . '" border="1" alt="" width="96" height="86" /></div>' : NULL;

    //per l'anteprima deve essere compilato il campo Testo, altrimenti mostro il messaggio di campo obbligatorio
    
    if (trim($testo) == '') {
        $div_preview = '<div id="error">' . $lang['anteprima'] . '</div><br />';
    }
    else {

        $div_preview = '
        <div align="center" class="text2" style="margin-bottom: 57px;"><b>' . $lang['preview'] . '</b><span id="preview_y" style="display: none;">
		<a href="javascript:void(0);" onclick="ShowHide()" class="piccolo">' . $lang['show_preview'] . '</a></span> <span id="preview_n" style="display: inline;">
		<a href="javascript:void(0);" onclick="ShowHide()" class="piccolo">' . $lang['hide_preview'] . '</a></span>
        <div id="preview" class="text" style="text-align: left; padding: 3px; border-style: solid; border-width: 1px; border-color: #DEE3E7; background-color: ' . $val_news_conf['sfondo_notizia'] . '; width: ' . $val_news_conf['larghezza'] . 'px;">' . $testo . '</div></div><br />';
    }

    //ridefinisco la variabili per visualizzarne correttamente il contenuto nel form
	$titolo_value = toUnicode(htmlspecialchars($_POST['titolo'], ENT_QUOTES, "ISO-8859-1"));
    $testo_value = toUnicode(htmlspecialchars($_POST['testo'], ENT_QUOTES, "ISO-8859-1"));
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES, "ISO-8859-1");
	$shortdescription = htmlspecialchars($_POST['shortdescription'], ENT_QUOTES, "ISO-8859-1");
    $data_pubb_disabled = (isset($_POST['cb_datafutura'])) ? '' : 'disabled="disabled"';
    $data_futura_checked = (isset($_POST['cb_datafutura'])) ? 'checked="checked"' : NULL;

    if ( isset($_POST['cb_datafutura']) && preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}\s\d{2}:\d{2}$/', $_POST['data_pubb']) ) {
		$data_pubb_value = $_POST['data_pubb'];
	} else {
		$data_pubb_value = strftime("%d/%m/%Y %H:%M", $q_riga['data_pubb']);
	} 
	
	$approva_checked = (isset($_POST['cb_approva'])) ? 'checked="checked"' : NULL;
    $insert_empty = NULL;
    $insert_msg_ok = NULL;

    //richiamo la funzione per l'upload
    
    if ($q_riga_perm['permessi'] == 'tutto' || $q_riga_perm['permessi'] == 'upload') {
        upload();
    }
}

//altrimenti, se ho cliccato sul bottone Modifica
elseif (isset($_POST['submit'])) {
    
    if (isset($_POST['cbcancella'])) {
        
        if (mysqli_query($db, "DELETE FROM `$tab_news` WHERE id=$idnews")) {
            $deleteok = '<div id="success">' . $lang['canc_news_ok'] . ' <img src="' . $img_path . '/attendi.gif" alt="" /></div><br />';
            header("Refresh: 2; url=inserisci.php");
        }
        else {
            $deleteok = '<div id="error">' . $lang['canc_news_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
        }
    }
    else {

        //richiamo la funzione per l'upload
        
        if ($q_riga_perm['permessi'] == 'tutto' || $q_riga_perm['permessi'] == 'upload') {
            upload();
        }

        //inizio controllo immagine di apertura
        immagine_apertura();
        $div_preview = NULL;
		//$titolo = toUnicode(htmlentities(htmlspecialchars($_POST['titolo'], ENT_QUOTES, "ISO-8859-1"), ENT_XHTML, "ISO-8859-1"));
		//$testo = toUnicode(htmlentities(htmlspecialchars($_POST['testo'], ENT_QUOTES, "ISO-8859-1"), ENT_XHTML, "ISO-8859-1"));
		$titolo = htmlspecialchars($_POST['titolo'], ENT_QUOTES, "ISO-8859-1");
		$testo = htmlspecialchars($_POST['testo'], ENT_QUOTES, "ISO-8859-1");
		$description = htmlspecialchars($_POST['description'], ENT_QUOTES, "ISO-8859-1");
		$shortdescription = htmlspecialchars($_POST['shortdescription'], ENT_QUOTES, "ISO-8859-1");
        $letture = (isset($_POST['letture'])) ? intval($_POST['letture']) : 0;
        $nosmile = (isset($_POST['nosmile'])) ? 1 : 0;
        $commenti = (isset($_POST['abilita_commenti'])) ? 1 : 0;
        $data_pubb_disabled = (isset($_POST['cb_datafutura'])) ? '' : 'disabled="disabled"';
		$data_futura_checked = (isset($_POST['cb_datafutura'])) ? 'checked="checked"' : NULL;

		//controllo eventuale data futura
		if ( isset($_POST['cb_datafutura']) ) {
			if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}\s\d{2}:\d{2}$/', $_POST['data_pubb'])) {
				$data_pubb = strtotime(str_replace('/', '-', $_POST['data_pubb']));
			} else {
				$data_pubb = $q_riga['data_pubb'];
			}
		} else {
			$data_pubb = $q_riga['data_pubb'];
		}

        if ($_SESSION['livello_id'] == 1) {
            $notifica_commenti = (isset($_POST['notifica_commenti'])) ? 1 : 0;
            $approva_news = (isset($_POST['cb_approva'])) ? 1 : 0;
        }
        else {
            $notifica_commenti = $q_riga['notifica_commenti'];
            $approva_news = $q_riga['news_approvata'];
        }
        
        if (trim($titolo) == '' || trim($testo) == '') {
            $insert_empty = '<div id="error">' . $lang['tit_text_obbl'] . '</div><br /';
            $insert_msg_ok = NULL;
        }
        else {
            $insert_empty = NULL;
			$testo = mysqli_real_escape_string($db, $testo);
            $titolo = mysqli_real_escape_string($db, $titolo);
            $immagine = mysqli_real_escape_string($db, $immagine);				
			
			//make connection PDO to get friendly URL
			if($titolo != $q_riga['titolo']){
				$servername = DATASERVER;
				$dbname = DATABASE;
				try{
					$db2 = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
					$db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$friendly_url = GetFriendLyUrl($titolo, $db2);
				}
				catch(PDOException $e)	{
					$query_msg = "Could not connect: " . $e->getMessage();
				}
				$db2 = NULL;
			}
            //in base ai permessi dell'utente, imposto la query con i campi che può modificare o meno
            
            switch ($q_riga_perm['permessi']) {
                case 'letture':
                    $query = "UPDATE `$tab_news` SET titolo='$titolo', testo='$testo', description='$description', shortdescription='$shortdescription', friendly_url='$friendly_url', id_cat=" . intval($_POST['categoria']) . ", letture=$letture, data_pubb=$data_pubb, immagine='$immagine', nosmile=$nosmile, news_approvata=$approva_news, abilita_commenti=$commenti, notifica_commenti=$notifica_commenti WHERE id=$idnews";
                break;
                case 'nessuno':
                    $query = "UPDATE `$tab_news` SET titolo='$titolo', testo='$testo', description='$description', shortdescription='$shortdescription', friendly_url='$friendly_url', id_cat=" . intval($_POST['categoria']) . ", data_pubb=$data_pubb, immagine='$immagine', nosmile=$nosmile, news_approvata=$approva_news, abilita_commenti=$commenti, notifica_commenti=$notifica_commenti WHERE id=$idnews";
                break;
                case 'upload':
                    $query = "UPDATE `$tab_news` SET titolo='$titolo', testo='$testo', description='$description', shortdescription='$shortdescription', friendly_url='$friendly_url', id_cat=" . intval($_POST['categoria']) . ", data_pubb=$data_pubb, immagine='$immagine', nosmile=$nosmile, news_approvata=$approva_news, abilita_commenti=$commenti, notifica_commenti=$notifica_commenti WHERE id=$idnews";
                break;
                case 'tutto':
                    $query = "UPDATE `$tab_news` SET titolo='$titolo', testo='$testo', description='$description', shortdescription='$shortdescription', friendly_url='$friendly_url', id_cat=" . intval($_POST['categoria']) . ", letture=$letture, data_pubb=$data_pubb, immagine='$immagine', nosmile=$nosmile, news_approvata=$approva_news, abilita_commenti=$commenti, notifica_commenti=$notifica_commenti WHERE id=$idnews";
                break;
            }
            
            if (mysqli_query($db, $query)) {
                $insert_msg_ok = '<div id="success">' . $lang['edit_news_ok'] . ' <img src="' . $img_path . '/attendi.gif" alt="" /></div><br />';
        		header("Refresh: 2; url=modifica.php?id=" . $idnews);
            }
            else {
                $insert_msg_ok = '<div id="error">' . $lang['edit_news_error'] . '</div><br /><span class="text2">' . mysqli_error($db) . '</span><br /><br />';
            }

            //dopo la modifica del'articolo svuoto i campi Titolo, Testo, Immagine
            $titolo = NULL;
            $testo = NULL;
			
            $immagine = NULL;
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">     
  <head>         
    <title><?php echo $lang['modifica']; ?>
    </title>         
    <link rel="stylesheet" href="../style.css" type="text/css" />		 
<script language="JavaScript" src="../javascript.js" type="text/JavaScript"></script>      
  </head>     
  <body>
<?php
require_once ("menu.php");

//visualizzo il div per l'anteprima o il messaggio di campi vuoti o di inserimento nel db
echo $div_preview;
echo $insert_empty;
echo $insert_msg_ok;
echo $deleteok;
?>         
    <form method="post" action="modifica.php?id=<?php echo $idnews; ?>" enctype="multipart/form-data" name="input_form">             
      <table width="100%" align="center" style="border: 3px solid #DDDDDD;" cellpadding="3" cellspacing="2">	                   
        <tr>                              
          <td bgcolor="#DEE3E7" width="21%" align="center" class="text"><b><?php echo $lang['titolo']; ?></b></td>                              
          <td bgcolor="#EEEEEE" align="left" class="text2">                         
            <input type="text" size="82" maxlength="150" name="titolo" tabindex="1" value="<?php echo $titolo_value; ?>" /><br />                         
            <img src="<?php echo $img_path; ?>/info2.gif" alt="Info" title="Info" />                           
            <?php
$TotaleCommenti = ($q_riga['TotaleCommenti'] > 0 && $_SESSION['livello_id'] == 1) ? '<a href="commenti.php?id_news=' . $q_riga['id'] . '">' . $q_riga['TotaleCommenti'] . '</a>' : $q_riga['TotaleCommenti'];
$link_leggi = ($q_riga['data_pubb'] < time() && $q_riga['news_approvata'] == 1) ? ' <a href="../view.php?id=' . $idnews . '" target="_blank" class="piccolo">' . $lang['leggi'] . ' <img src="' . $img_path . '/nw.gif" border="0" alt="" title="" /></a>' : '';
$info_approvata = ($q_riga['news_approvata'] == 0) ?  '<b>' . $lang['news_approvata'] . '</b> ' . $lang['no'] : NULL;
echo $link_autore . ' <b>' . $lang['data'] . '</b> ' . $data . ' <b>' . $lang['categoria'] . '</b> ' . $q_riga['nome_categoria'] . '  <b>' . $lang['letture'] . '</b> ' . number_format($q_riga['letture'], 0, '', '.') . ' <b>' . $lang['commenti'] . '</b> ' . $TotaleCommenti . ' <b>IP</b> ' . $ip . ' ' . $link_leggi . ' ' . $info_approvata; ?></td>                        
        </tr>  
		<tr>                              
          <td bgcolor="#DEE3E7" width="21%" align="center" class="text"><b>Description for Search</b></td>                              
          <td bgcolor="#EEEEEE" align="left">                         
            <input type="text" size="82" maxlength="150" name="description" tabindex="2" value="<?php echo $description; ?>" /></td>                        
        </tr>   
		<tr>                              
          <td bgcolor="#DEE3E7" width="21%" align="center" class="text"><b>Short Description</b></td>                              
          <td bgcolor="#EEEEEE" align="left">                         
            <input type="text" size="82" maxlength="150" name="shortdescription" tabindex="2" value="<?php echo $shortdescription; ?>" /></td>                        
        </tr>                        
		                     
        <tr>                     
          <td bgcolor="#DEE3E7" align="center" class="text2">                         
            <?php echo $lang['codes']; ?></td>                     
          <td align="left" bgcolor="#EEEEEE">                         
            <!-- formattazione testo con BBcode-->   
			<input type="button" value="h2" style="width: 28px; font-size: 0.8em; font-weight: bold;" onclick="addText(' [h2][/h2]'); return(false);" onmouseover="helpline('h2')" /> 
			<input type="button" value="h3" style="width: 28px; font-size: 0.8em; font-weight: bold;" onclick="addText(' [h3][/h3]'); return(false);" onmouseover="helpline('h3')" />
			<input type="button" value="p" style="width: 28px; font-size: 0.8em; font-weight: bold;" onclick="addText(' [p][/p]'); return(false);" onmouseover="helpline('para')" />  
			<input type="button" value="br" style="width: 28px; font-size: 0.8em; font-weight: bold;" onclick="addText(' [br]'); return(false);" onmouseover="helpline('br')" />                        
            <input type="button" value="b" style="width: 28px; font-size: 0.8em; font-weight: bold;" onclick="addText(' [b][/b]'); return(false);" onmouseover="helpline('b')" />                         
            <input type="button" value="i" style="width: 28px; font-size: 0.8em; font-style: italic;" onclick="addText(' [i][/i]'); return(false);" onmouseover="helpline('i')" />                         
            <input type="button" value="u" style="width: 28px; font-size: 0.8em; text-decoration: underline;" onclick="addText(' [u][/u]'); return(false);" onmouseover="helpline('u')" />                         
            <input type="button" value="Img" style="width: 38px; font-size: 0.8em;" onclick="addText(' [img][/img]'); return(false);" onmouseover="helpline('g')" />                         
            <input type="button" value="Email" style="width: 45px; font-size: 0.8em;" onclick="addText(' [email][/email]'); return(false);" onmouseover="helpline('a')" />                         
            <input type="button" value="URL" style="width: 38px; font-size: 0.8em;" onclick="addText(' [url][/url]'); return(false);" onmouseover="helpline('w')" />                         
            <input type="button" value="Callto" style="width: 50px; font-size: 0.8em;" onclick="addText(' [callto][/callto]'); return(false);" onmouseover="helpline('v')" />                         
            <input type="button" value="Video" style="width: 46px; font-size: 0.8em;" onclick="addText(' [yt][/yt]'); return(false);" onmouseover="helpline('y')" />                         
            <input type="button" value="List" style="width: 40px; font-size: 0.8em;" onclick="addText('[ul]\n[li]uno[/li]\n[li]due[/li]\n[/ul]'); return(false);" onmouseover="helpline('l')" />                         
            <input type="button" value="Quote" style="width: 50px; font-size: 0.8em;" onclick="addText(' [quote][/quote]'); return(false);" onmouseover="helpline('q')" />                         
            <input type="button" value="Code" style="width: 53px; font-size: 0.8em;" onclick="addText(' [code][/code]'); return(false);" onmouseover="helpline('c')" /> 
			<input type="button" value="Map" style="width: 53px; font-size: 0.8em;" onclick="addText(' [gmap][/gmap]'); return(false);" onmouseover="helpline('p')" />                         
			<select name="im" onchange="if(this.selectedIndex!=0)this.form.testo.value+=this.options[this.selectedIndex].value;" style="width: 58px; font-size: 0.7em;" onmouseover="helpline('m')">                             
              <option selected="selected">IM</option>                             
              <option value="[aim]<?php echo $im_num; ?>[/aim]" title="AOL Instant Messenger">AIM</option>
              <option value="[icq]<?php echo $im_num; ?>[/icq]" title="ICQ">ICQ</option>
              <option value="[sky]<?php echo $im_num; ?>[/sky]" title="Skype">Skype</option>
              <option value="[yim]<?php echo $im_num; ?>[/yim]" title="Yahoo! Messenger">Y!M</option>
            </select>            
			<select name="size" onchange="if(this.selectedIndex!=0)this.form.testo.value+=this.options[this.selectedIndex].value;" style="width: 106px; font-size: 0.8em;" onmouseover="helpline('s')">                             
              <option selected="selected"><?php echo $lang['dim_normale']; ?>               
              </option>                             
              <option value="[size=8][/size]"><?php echo $lang['dim_piccolo']; ?>               
              </option>                             
              <option value="[size=12][/size]"><?php echo $lang['dim_grande']; ?>                 
              </option>                             
              <option value="[size=16][/size]"><?php echo $lang['dim_mgrande']; ?>                
              </option>                        
            </select>
            <select name="color" onchange="if(this.selectedIndex!=0)this.form.testo.value+=this.options[this.selectedIndex].value;" style="width: 60px; font-size: 0.8em;" onmouseover="helpline('r')">                             
              <option selected="selected">Color</option>                             
              <option value="[color=blue][/color]" style="background: blue; color: white;" title="Blue">Blue</option>
              <option value="[color=red][/color]" style="background: red; color: white;" title="Red">Red</option>
            </select>
            <br />                         
            <!--<input type="text" name="helpbox" readonly="readonly" style="width:100%; background-color:#EEEEEE; border-style: none; font-size: 0.7em; font-family: verdana;" />-->
			<label id="helpbox" style="width:100%; background-color:#EEEEEE; border-style: none; font-size: 0.7em; font-family: verdana;"></label>                         
            <!-- fine formattazione testo con BBcode--></td>                    
        </tr>                      
        <tr>                            
          <td bgcolor="#DEE3E7" valign="top" align="center" class="text"><b><?php echo $lang['testo']; ?></b><br /><br />	      			                          
            <!-- inserimento smilies -->                                     
            <a href="#" onclick="addText(' :cool:'); return(false);">              
              <img src="<?php echo $img_path; ?>/cool.gif" border="0" alt="" /></a> &nbsp;   
                                              
            <a href="#" onclick="addText(' :)'); return(false);">              
              <img src="<?php echo $img_path; ?>/smile.gif" border="0" alt="" /></a> &nbsp;   
                                              
            <a href="#" onclick="addText(' :lol:'); return(false);">              
              <img src="<?php echo $img_path; ?>/tongue.gif" border="0" alt="" /></a> &nbsp; 
                                                
            <a href="#" onclick="addText(' :D'); return(false);">              
              <img src="<?php echo $img_path; ?>/biggrin.gif" border="0" alt="" /></a> &nbsp; 
                                               
            <a href="#" onclick="addText(' ;)'); return(false);">              
              <img src="<?php echo $img_path; ?>/wink.gif" border="0" alt="" /></a> &nbsp;   
                                 
            <a href="#" onclick="addText(' :o'); return(false);">              
              <img src="<?php echo $img_path; ?>/ohh.gif" border="0" alt="" /></a> <br /><br />   
                                          
            <a href="#" onclick="addText(' :('); return(false);">              
              <img src="<?php echo $img_path; ?>/sad.gif" border="0" alt="" /></a> &nbsp; 
              
            <a href="#" onclick="addText(' :dotto:'); return(false);">              
              <img src="<?php echo $img_path; ?>/dotto.gif" border="0" alt="" /></a> &nbsp;     
                                          
            <a href="#" onclick="addText(' :wtf:'); return(false);">              
              <img src="<?php echo $img_path; ?>/parolaccia.gif" border="0" alt="" /></a> &nbsp; 
              
            <a href="#" onclick="addText(' :ehm:'); return(false);">              
              <img src="<?php echo $img_path; ?>/stordito.gif" border="0" alt="" /></a> &nbsp; 
              
            <a href="#" onclick="addText(' :info:'); return(false);">              
              <img src="<?php echo $img_path; ?>/info.png" border="0" alt="" /></a> &nbsp;        
                                       
            <a href="#" onclick="addText(' :star:'); return(false);">              
              <img src="<?php echo $img_path; ?>/star.png" border="0" alt="" /></a> <br /><br />  
                                          
            <a href="#" onclick="addText(' :alert:'); return(false);">              
              <img src="<?php echo $img_path; ?>/alert.png" border="0" alt="" /></a> &nbsp;    
                                              
            <a href="#" onclick="addText(' :???:'); return(false);">              
              <img src="<?php echo $img_path; ?>/question.png" border="0" alt="" /></a> &nbsp;  
                                          
            <a href="#" onclick="addText(' :check:'); return(false);">              
              <img src="<?php echo $img_path; ?>/check.png" border="0" alt="" /></a> &nbsp;    
                   
            <a href="#" onclick="addText(' :wiki:'); return(false);">              
              <img src="<?php echo $img_path; ?>/wikipedia.png" border="0" alt="" /></a> &nbsp;  
                                               
            <a href="#" onclick="addText(' :comm:'); return(false);">              
              <img src="<?php echo $img_path; ?>/comm.png" border="0" alt="" /></a> &nbsp;     
                                             
            <a href="#" onclick="addText(' :www:'); return(false);">              
              <img src="<?php echo $img_path; ?>/www.png" border="0" alt="" /></a> <br /><br />               
                                
            <a href="#" onclick="addText(' :fb:'); return(false);">              
              <img src="<?php echo $img_path; ?>/facebook.gif" border="0" alt="" title="Facebook" /></a> &nbsp;  
                                      
            <a href="#" onclick="addText(' :tw:'); return(false);">              
              <img src="<?php echo $img_path; ?>/twitter.png" border="0" alt="" title="Twitter" /></a> &nbsp;
              
	          <a href="#" onclick="addText(' :g+:'); return(false);">              
              <img src="<?php echo $img_path; ?>/gplus.png" border="0" alt="" title="Google Plus" /></a> &nbsp;
              
            <a href="#" onclick="addText(' :li:'); return(false);">              
              <img src="<?php echo $img_path; ?>/linkedin.gif" border="0" alt="" title="Linkedin" /></a> &nbsp;  
                                       
            <a href="#" onclick="addText(' :pi:'); return(false);">              
              <img src="<?php echo $img_path; ?>/pinterest.png" border="0" alt="" title="Pinterest" /></a> &nbsp; 
              
            <a href="#" onclick="addText(' :tu:'); return(false);">              
              <img src="<?php echo $img_path; ?>/tumblr.png" border="0" alt="" title="Tumblr" /></a> <br /><br />  
                      
	          <a href="#" onclick="addText(' :yt:'); return(false);">              
              <img src="<?php echo $img_path; ?>/youtube.png" border="0" alt="" title="YouTube" /></a> &nbsp; 	
              
	          <a href="#" onclick="addText(' :st:'); return(false);">              
              <img src="<?php echo $img_path; ?>/steam.gif" border="0" alt="" title="Steam" /></a> &nbsp;   
                                     
	          <a href="#" onclick="addText(' :fl:'); return(false);">              
              <img src="<?php echo $img_path; ?>/flickr.png" border="0" alt="" title="Flickr" /></a> &nbsp;
              
              <a href="#" onclick="addText(' :sp:'); return(false);">
			  <img src="<?php echo $img_path; ?>/spotify.png" border="0" alt="" title="Spotify" /></a> &nbsp; 		
			  	          
              <a href="#" onclick="addText(' :ig:'); return(false);">
			  <img src="<?php echo $img_path; ?>/instagram.png" border="0" alt="" title="Instagram" /></a> &nbsp; 	
			  		 
			  <a href="#" onclick="addText(' :dx:'); return(false);">
			  <img src="<?php echo $img_path; ?>/dx.png" border="0" alt="" title="Dx" /></a> <br /><br /> 
			   	
			  <a href="#" onclick="addText(' :wa:'); return(false);">              
              <img src="<?php echo $img_path; ?>/whatsapp.png" border="0" alt="" title="WhatsApp" /></a> &nbsp;  
                  
              <a href="#" onclick="addText(' :appl:'); return(false);">
			  <img src="<?php echo $img_path; ?>/apple.png" border="0" alt="" title="Apple" /></a> &nbsp; 	
			  	  
              <a href="#" onclick="addText(' :andr:'); return(false);">
			  <img src="<?php echo $img_path; ?>/android.png" border="0" alt="" title="Android" /></a> &nbsp; 	
			  		  
			  <a href="#" onclick="addText(' :lin:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_tux.png" border="0" alt="" title="Linux" /></a> &nbsp; 
			  			  
			  <a href="#" onclick="addText(' :win:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_win.jpg" border="0" alt="" title="Windows" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :dwnl:'); return(false);">  
			  <img src="<?php echo $img_path; ?>/icon_download.png" border="0" alt="" title="Download" /></a> <br /><br /> 
			  
              <a href="#" onclick="addText(' :gpx:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_gpx.gif" border="0" alt="" title="Gpx" /></a>	&nbsp;
			  
			  <a href="#" onclick="addText(' :kml:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_kml.png" border="0" alt="" title="Kml" /></a>	&nbsp; 
			  
			  <a href="#" onclick="addText(' :kmz:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_kmz.png" border="0" alt="" title="Kmz" /></a>	&nbsp; 
			  
			  <a href="#" onclick="addText(' :rar:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_rar.gif" border="0" alt="" title="Rar" /></a>	&nbsp; 
			  
			  <a href="#" onclick="addText(' :zip:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_zip.gif" border="0" alt="" title="Zip" /></a>	&nbsp; 
			  
			  <a href="#" onclick="addText(' :trn:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_torrent.png" border="0" alt="" title="Torrent" /></a>	<br /><br /> 
			  
			  <a href="#" onclick="addText(' :tel:'); return(false);">              
              <img src="<?php echo $img_path; ?>/tel.png" border="0" alt="" /></a> &nbsp;            
                          
			  <a href="#" onclick="addText(' :email:'); return(false);">              
              <img src="<?php echo $img_path; ?>/mail.png" border="0" alt="" /></a> &nbsp; 
              
			  <a href="#" onclick="addText(' :doc:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_doc.gif" border="0" alt="" title="Doc" /></a> &nbsp; 
			  	
			  <a href="#" onclick="addText(' :xls:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_xls.gif" border="0" alt="" title="Xls" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :pdf:'); return(false);">
			  <img src="<?php echo $img_path; ?>/pdf.gif" border="0" alt="" title="Pdf" /></a>	&nbsp; 
			  
			  <a href="#" onclick="addText(' :xml:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_xml.png" border="0" alt="" title="Xml" /></a> <br /><br /> 
			  
			  <a href="#" onclick="addText(' :man:'); return(false);">
			  <img src="<?php echo $img_path; ?>/profilo.png" border="0" alt="" title="Profilo" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :jpg:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_jpg.png" border="0" alt="" title="Jpg" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :psd:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_psd.png" border="0" alt="" title="Psd" /></a>  &nbsp; 
			  
			  <a href="#" onclick="addText(' :clo:'); return(false);">
			  <img src="<?php echo $img_path; ?>/clock.png" border="0" alt="" title="Clock" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :home:'); return(false);">
			  <img src="<?php echo $img_path; ?>/icon_home.png" border="0" alt="" title="Home" /></a> &nbsp; 
			  
			  <a href="#" onclick="addText(' :mk:'); return(false);">
			  <img src="<?php echo $img_path; ?>/marker.png" border="0" alt="" title="Marker" /></a> 
            <!-- fine inserimento smilies --></td>                              
          <td align="left" bgcolor="#EEEEEE">            
<textarea cols="118" rows="24" name="testo" id="testo" tabindex="2"><?php echo $testo_value; ?></textarea></td>                        
        </tr>                        
        <tr>                              
          <td bgcolor="#DEE3E7" align="center" class="text"><b><?php echo $lang['img_apertura']; ?></b></td>                              
          <td bgcolor="#EEEEEE" align="left" height="30" class="text2">                                  
            <input type="text" id="immagine" size="80" maxlength="100" name="immagine" value="<?php echo $immagine; ?>" /> 373 x 270</td>                        
        </tr>                        
        <?php echo permessi(); ?>                          
        <tr>                              
          <td bgcolor="#DEE3E7" align="center" class="text"><b><?php echo $lang['opzioni']; ?></b></td>                              
          <td bgcolor="#EEEEEE" align="left" height="30" class="text2">                         
            <select name="categoria">
			<?php
			$cat_sel = mysqli_query($db, "SELECT id_cat, nome_categoria FROM `$tab_categorie` ORDER BY nome_categoria ASC");
			while ($row_sel = mysqli_fetch_array($cat_sel)) {
				$categoria_selected = ($row_sel['id_cat'] == $q_riga['id_cat']) ? ' selected="selected"' : NULL;
				echo '<option value="' . $row_sel['id_cat'] . '"' . $categoria_selected . '> ' . $row_sel['nome_categoria'] . '</option>';
			}
			?>
            </select> <input type="checkbox" id="nosmile" name="nosmile" <?php echo $nosmile_checked; ?> /><label for="nosmile"><?php echo $lang['nosmilies']; ?></label> <input type="checkbox" id="cbcancella" name="cbcancella" onclick="if (this.checked) { alert('<?php echo $lang['attenzione_news']; ?>'); }" /><label for="cbcancella"><span style="color: rgb(255, 0, 0);"><?php echo $lang['canc_news']; ?></span></label> <input type="checkbox" id="abilita_commenti" name="abilita_commenti" <?php echo $commenti_checked; ?> /><label for="abilita_commenti"><?php echo $lang['commenti_on']; ?></label>
<?php

if ($_SESSION['livello_id'] == 1) {
    echo '<input type="checkbox" id="notifica_commenti" name="notifica_commenti" ' . $notifica_commenti_checked . ' /><label for="notifica_commenti">' . $lang['commenti_email'] . '</label> <input type="checkbox" name="cb_approva" id="cb_approva" ' . $approva_checked . ' /><label for="cb_approva">' . $lang['news_approvata'] . '</label>';
}

if ($q_riga_perm['autorizza_news'] == 0) {
    echo ' - <span style="color: rgb(255, 0, 0);">' . $lang['user_autorizza_news'] . '</span>';
}
?> <input type="text" id="data_pubb" size="14" maxlength="16" name="data_pubb" value="<?php echo $data_pubb_value; ?>" <?php echo $data_pubb_disabled; ?> /><input type="checkbox" onclick="datafutura()" id="cb_datafutura" name="cb_datafutura" value="ON" <?php echo $data_futura_checked; ?> /><label for="cb_datafutura"><span style="cursor: help; border-bottom: 1px dotted #000;" title="gg/mm/aaaa hh:mm"><?php echo $lang['data_pubblicazione']; ?></span></label>
</td>        
        </tr>                        
        <tr>                              
          <td bgcolor="#DEE3E7" align="center" colspan="2" class="text2">
            <input type="submit" value="<?php echo $lang['btn_modifica']; ?>" name="submit" style="font-weight: bold;" tabindex="3" />                           
            <input type="submit" value="<?php echo $lang['btn_preview']; ?>" name="preview" />                           
            <input type="reset" value="<?php echo $lang['btn_cancella']; ?>" name="reset" />      </td>                        
        </tr>                 
      </table>         
    </form>
<script language="JavaScript" type="text/javascript"> document.input_form.titolo.focus(); </script><br />         
    <?php require_once ("footer.php"); mysqli_close($db);  ?>      
  </body>
</html>