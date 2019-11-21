<?php
header('Content-type: text/plain');
$plik = fopen($_GET['id'].'.txt', 'r');
while (!feof($plik)) {
  $s = fgets($plik);
  echo $s;
}
fclose($plik);
?>
