<?php

echo $_POST['message'] . " " . $_POST['username'];

if (isset($_POST['message']) && isset($_POST['username'])) {
    // $messages = file("chat.txt");
    $file = fopen("chat.txt", "a");
    flock($file, LOCK_EX);

    // if ((int)$numberOfMsgs[0] > 100) {
    //     unset($messages[1]);
    // } else {
    //     $messages[0] = strval((int)$messages[0] + 1);
    // }

    $log = $_POST['username'] . ': ' . $_POST['username'];
    fwrite($file, $log);
    // array_push($messages, $log);

    // foreach($messages as $msg) {
    //     fwrite($file, $msg . "\r\n");
    // }

    flock($file, LOCK_UN);
    fclose($file);
}

?>