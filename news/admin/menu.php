<?php


if (session_id() == '') {
    session_start();
}

if (basename($_SERVER['SCRIPT_NAME']) == 'menu.php') {
    die("Internal file");
}

//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

//se non c'è la sessione 'loggato' rimando alla pagina di login

if (!isset($_SESSION['loggato'])) {
    header('Location: ' . $dir_admin . '/login.php');
    exit();
}

if ($_SESSION['livello_id'] == 1) {

    //menu di navigazione amministratore
    echo '<table width="100%" cellpadding="1" cellspacing="0" border="0" align="center">
    <tr> 
      <td width="15%" align="left" valign="top"><img src="' . $img_path . '/logonews.gif" border="0" alt="Logo" /></td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="text2" align="right">' . $_SESSION['nome_cognome_sess'] . '<br /><a href="logout.php" class="piccolo"><b>' . $lang['logout'] . '</b></a><br /><br /><br /></td>
    </tr>
    <tr>
		<td class="toprow" align="center"><img src="' . $img_path . '/insert.png" border="0" alt="" /> <a href="sendsms.php">Send SMS</a></td>
	  <td class="toprow" align="center"><img src="' . $img_path . '/insert.png" border="0" alt="" /> <a href="couponcode.php">Coupon</a></td>
	  <td class="toprow" align="center"><img src="' . $img_path . '/insert.png" border="0" alt="" /> <a href="Order.php">Orders</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/insert.png" border="0" alt="" /> <a href="inserisci.php">' . $lang['inserisci'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/news.png" border="0" alt="" /> <a href="gestione_news.php">' . $lang['gestione_news'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/categorie.gif" border="0" alt="" /> <a href="categorie.php">' . $lang['categorie'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/comm.png" border="0" alt="" /> <a href="commenti.php">' . $lang['commenti'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/search.png" border="0" alt="" /> <a href="searchadmin.php">' . $lang['ricerca_news'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/utenti.png" border="0" alt="" /> <a href="utenti.php">' . $lang['elenco_utenti'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/profilo.png" border="0" alt="" /> <a href="profilo_admin.php?user_id=' . $_SESSION['user_id'] . '">' . $lang['profilo_admin'] . '</a></td>
      <td class="toprow" align="center"><img src="' . $img_path . '/impost.png" border="0" alt="" /> <a href="impostazioni.php">' . $lang['impostazioni'] . '</a></td>
    </tr>
  </table>
<br />';
}
else {

    //menu di navigazione utente
    echo '<table width="100%" cellpadding="1" cellspacing="0" border="0" align="center">
    <tr> 
      <td width="20%" align="left" valign="top"><img src="' . $img_path . '/logonews.gif" border="0" alt="Logo" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="20%" class="text2" align="right">' . $_SESSION['nome_cognome_sess'] . '<br /><a href="logout.php" class="piccolo"><b>' . $lang['logout'] . '</b></a><br /><br /><br /></td>
    </tr>
    <tr>
      <td width="20%" class="toprow" align="center"><img src="' . $img_path . '/insert.png" border="0" alt="" /> <a href="inserisci.php">' . $lang['inserisci'] . '</a></td>
      <td width="20%" class="toprow" align="center"><img src="' . $img_path . '/news.png" border="0" alt="" /> <a href="elenco_news.php">' . $lang['gestione_news'] . '</a></td>
      <td width="20%" class="toprow" align="center"><img src="' . $img_path . '/search.png" border="0" alt="" /> <a href="searchadmin.php">' . $lang['ricerca_news'] . '</a></td>
      <td width="20%" class="toprow" align="center"><img src="' . $img_path . '/profilo.png" border="0" alt="" /> <a href="profilo_utente.php">' . $lang['profilo_utente'] . '</a></td>
   </tr>
  </table>
<br />';
}
?>