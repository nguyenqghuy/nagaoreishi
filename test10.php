<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php  
if(empty(strstr("hello world", "world"))){ echo "cannot find";}
else { echo "yes can find";}
echo "<br/>" . strpos("This is a strpos() test", "This");
if(strpos("This is a strpos() test", "This") === false){ echo "cannot find";}
else {echo "yes can find";}
echo "<br/>" . $_SERVER['DOCUMENT_ROOT'];
echo "<br/>" . $_SERVER["SERVER_NAME"];
echo phpinfo();

?>
</body>
</html>
