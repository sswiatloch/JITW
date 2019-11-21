<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Lab PHP</title>
</head>
<body>
	<?php 
		function witaj($name) {
			return 'Witaj '.$name.'!';
		}

		$var = $_POST['name'];
		if ($var == "Stanisław Światłoch") {
			echo witaj($var);
		}
		else if (is_numeric($var)) {
			for($i=1; $i<=$var; $i++){
				echo $i.'<br/>';
			}

		} else {
			echo 'Dostęp odmówiony';
		}
	?>
</body>
</html>
