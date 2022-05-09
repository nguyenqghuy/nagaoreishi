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
require_once (dirname(__FILE__) . '/../config.php');
$db = @mysqli_connect($db_host, $db_user, $db_password, $db_name);

@mysqli_query($db, "UPDATE `$tab_utenti` SET token=NULL, cookie=0 WHERE user_id=" . intval($_SESSION['user_id']));
@mysqli_close($db);

$_SESSION = array();
session_destroy();

if (isset($_COOKIE['session_name()'])) {
    setcookie(session_name(), '', time() - 3600);
}

if (isset($_COOKIE['accesso_news'])) {
    setcookie('accesso_news', '', time() - 3600, "/" . $news_dir);
}

if (isset($_GET['ref']) && $_GET['ref'] === 'c') {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">     
<head>
<title>Logout</title>  
</head>
<body>
<script type="text/javascript" type="text/JavaScript">
<!--
setTimeout("window.close()", 1000);
if(window.opener && !window.opener.closed) {
    window.opener.location.reload();
}
//-->
</script>
</body>
</html>';
}
else {
    header('Location: ' . $dir_admin . '/login.php');
    exit();
}
?>