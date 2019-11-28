<?php
/*
if(isset($_POST['opis'])){
echo $_POST['opis'];
} else {
echo "nie ma";
}*/

$folder = $_POST['blog_name'];
if(!file_exists("$folder")){
	mkdir("$folder", 0777);
	$file = fopen("$folder/info.txt", 'w');
	fwrite($file, $_POST['user_name']."\n");
	$password_md5 = md5($_POST['password']);
	fwrite($file, $password_md5."\n");
	fwrite($file, $_POST['opis']."\n");
	fclose($file);
	chmod($file, 0777);
} else {
	echo 'Blog o takiej nazwie już istnieje!';
}
?>

