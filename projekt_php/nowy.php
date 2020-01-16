<?php
if (!empty($_POST)) {
	$blog_name = str_replace('.','',$_POST['blog_name']);
	$blog_path = './blogs/' . $blog_name;

	if (empty($_POST['blog_name']) || empty($_POST['user_name']) || empty($_POST['password']) || empty($_POST['opis'])) {
		echo 'Nie uzupełniono wszystkich pól!';
	} else if (!file_exists($blog_path)) {
		$old = umask(0);
		if (!mkdir($blog_path)) echo 'Nie udało się utworzyć bloga!';

		$file = fopen($blog_path . '/info', 'w');
		flock($file, LOCK_EX);
		fwrite($file, $_POST['user_name'] . "\n");
		$password_md5 = md5($_POST['password']);
		fwrite($file, $password_md5 . "\n");
		fwrite($file, $_POST['opis'] . "\n");
		flock($file, LOCK_UN);
		fclose($file);

		chmod($blog_path, 0777);
		chown($blog_path, 'swiastan');
		chgrp($blog_path, 'students');

		chmod($file, 0777);
		chown($file, 'swiastan');
		chgrp($file, 'students');

		umask($old);

		header('Location: http://'.$_SERVER['HTTP_HOST'].'/~swiastan/projekt_php/blog.php?blog='.$blog_name);
        exit;
	} else {
		echo 'Blog o takiej nazwie już istnieje!';
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Nowy blog</title>
</head>

<body>
	<form action="nowy.php" method="post">
		<label> Nazwa blogu: </label> <input type="text" name="blog_name" /><br />
		<label> Nazwa użytkownika: </label> <input type="text" name="user_name" /><br />
		<label> Hasło: </label> <input type="password" name="password" /><br />
		<label> Opis: </label> <textarea name="opis" rows="8" cols="64" method="post"></textarea><br />
		<input type="submit" value="Wyślij" /><br />
		<input type="reset" value="Wyczyść formularz"><br />
	</form>

	<?php 
	include 'chat.html';
	include 'menu.php';
	?>

</body>

</html>
