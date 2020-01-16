<?php
if(empty($_POST ['blog'])||empty($_POST['wpis'])){
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/~swiastan/projekt_php/blog.php');
    exit;
}

if (!empty($_POST)) {
    if (empty($_POST['name']) || empty($_POST['content'])) {
        echo 'Uzupełnij wszystkie pola';
    } else {
        $comment_path = './blogs/'.$_POST['blog'].'/'.$_POST['wpis'].'.k';
        if (!file_exists($comment_path)){
            mkdir($comment_path);
            chmod($comment_path, 0777);
            chown($comment_path, 'swiastan');
            chgrp($comment_path, 'students');
        }

        $comment_count = count(array_diff(scandir($comment_path), ['.', '..']));

        $file = fopen($comment_path.'/'.$comment_count, 'w');
	flock($file, LOCK_EX);
        
        fwrite($file, $_POST['type']."\n");
        fwrite($file, date('Y-m-d, H:i:s')."\n");
        fwrite($file, $_POST['name']."\n");
        fwrite($file, $_POST['content']."\n");

	flock($file, LOCK_UN);

        fclose($file);
        chmod($file, 0777);
        chown($file, 'swiastan');
        chgrp($file, 'students');

        header('Location: http://'.$_SERVER['HTTP_HOST'].'/~swiastan/projekt_php/blog.php?blog='.$_POST['blog']);
        exit;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tworzenie komentarza</title>
</head>

<body>
    <?php
        echo '<p> Komentarz do wpisu '.$_POST['wpis'].' na blogu '.$_POST['blog'].'</p>';
    ?>
    <form action="koment.php" method="post">
        <label> Rodzaj komentarza: </label>
        <label><input type="radio" name="type" value="positive" /> Pozytywny </label>
        <label><input type="radio" name="type" value="neutral" checked /> Neutralny </label>
        <label><input type="radio" name="type" value="negative" /> Negatywny </label><br/>
        <label> Treść komentarza: </label> <textarea name="content" rows="8" cols="64" method="post"></textarea><br/>
        <label> Podpis: </label> <input type="text" name="name"/><br/>
        <input type="submit" value="Wyślij" /><br />
        <input type="reset" value="Wyczyść formularz"><br />
        <input type="hidden" name="blog" value="<?php echo $_POST['blog'];?>"/>
        <input type="hidden" name="wpis" value="<?php echo $_POST['wpis'];?>"/>
    </form>

    <?php 
	include 'chat.html';
	include 'menu.php';
	?>

</body>

</html>
