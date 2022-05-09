<?php


if (basename($_SERVER['SCRIPT_NAME']) == 'footer.php') {
    die("Internal file");
}

if (session_id() == '') {
    session_start();
}

//includo i file di configurazione
require_once (dirname(__FILE__) . '/../config.php');
require_once (dirname(__FILE__) . '/../lang/' . $language . '.php');

if (!isset($_SESSION['loggato'])) {
    header('Location: ' . $dir_admin . '/login.php');
    exit();
}
?>
<div id="footer" class="text2"><!-- (C) Spacemarc News --> Spacemarc News <?php echo $version; ?> &copy; <a href="http://www.spacemarc.it" target="_blank" class="piccolo">Spacemarc.it</a>  
<?php
//$db = NULL;
/*mysqli_close($db);
*///tempo generazione pagina (2a parte)
$mtime2 = @explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
$totaltime = ($endtime - $starttime);
$totaltime = @number_format($totaltime, 3);
echo ' - ' . $lang['time_gen'] . ' ' . $totaltime . ' sec. - ' . strftime("%a %d %b %Y %H:%M");
?> 
</div>