<?php
$file = fopen("chat.txt", "r");
flock($file, LOCK_EX);
fgets($file);
$data = "";

while (!feof($file)) {
     $data .= fgets($file);
}

flock($file, LOCK_UN);
fclose($file);

echo $data;

while(true) {
    echo "siemka";
    if ( connection_aborted() ) break;
    sleep(1);
}

?>