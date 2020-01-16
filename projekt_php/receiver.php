<?php

if (isset($_GET['message']) && isset($_GET['username'])) {
    $messages = file("chat.txt");
    $file = fopen("chat.txt", "w+");
    flock($file, LOCK_EX);

    if ((int)$messages[0] > 20) {
        unset($messages[1]);
    } else {
        $messages[0] = strval((int)$messages[0] + 1)."\r\n";
    }

    $log = $_GET['username'] . ': ' . $_GET['message'] . "\r\n";
    // fwrite($file, $log);
    array_push($messages, $log);

    foreach($messages as $msg) {
        fwrite($file, $msg);
    }

    flock($file, LOCK_UN);
    fclose($file);
}

?>