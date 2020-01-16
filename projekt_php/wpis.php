<?php
if (!empty($_POST)) {
    if (empty($_POST['user_name']) || empty($_POST['password']) || empty($_POST['wpis']) || empty($_POST['date']) || empty($_POST['time'])) {
        echo 'Uzupełnij wszystkie pola!';
    } else {
        $user_name = $_POST['user_name'];
        $password = md5($_POST['password']);
        $wpis = $_POST['wpis'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $number_of_files = $_POST['number_of_files'];


        $all_blogs = array_diff(scandir('./blogs'), ['.', '..']);

        $flag = true;
        foreach ($all_blogs as $blog) {
            $info = file_get_contents('./blogs/' . $blog . '/info');
            $lines = explode("\n", $info);

            if ($lines[0] == $user_name && $lines[1] == $password) {
                $flag = false;
                $blog_name = $blog;
                break;
            }
        }

        if ($flag) {
            echo 'Podany użytkownik nie posiada bloga!';
        } else {
            $file_name = str_replace('-', '', $date) . str_replace(':', '', $time) . date('s');
            $files = array_diff(scandir('./blogs/' . $blog_name), ['.', '..']);

            $number = 0;

            while (file_exists('./blogs/' . $blog_name . $file_name . ($number < 10 ? '0' . $number : $number))) {
                $number++;
            }

            $file_name .= ($number < 10 ? '0' . $number : $number);

            $file = fopen('./blogs/' . $blog_name . '/' . $file_name, 'w');
            flock($file, LOCK_EX);
            fwrite($file, $wpis);
            flock($file, LOCK_UN);
            fclose($file);

            chmod($file, 0777);
            chown($file, 'swiastan');
            chgrp($file, 'students');

            for ($i = 0; $i < $number_of_files; $i++) {
                $ext = pathinfo($_FILES['file' . $i]['name'], PATHINFO_EXTENSION);
                $newname = $file_name . $i . '.' . $ext;
                $target = './blogs/' . $blog_name . '/' . $newname;
                move_uploaded_file($_FILES['file' . $i]['tmp_name'], $target);
            }

            //header('Location: http://'.$_SERVER['HTTP_HOST'].'/~swiastan/projekt_php/blog.php?blog='.$blog_name);
            header('localhost/JITW/projekt_php/blog.php?blog=' . $blog_name);
            exit;
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wpis do bloga</title>
    <script type="text/javascript">
        var fileCount = 0;

        function makeTwoDigitNumber(n) {
            return n < 10 ? "0" + n : n;
        }

        function getCurrentDate() {
            var date = new Date();
            return date.getFullYear() + '-' + makeTwoDigitNumber(date.getMonth() + 1) + '-' + makeTwoDigitNumber(date.getDate());
        }

        function getCurrentTime() {
            var date = new Date();
            return makeTwoDigitNumber(date.getHours()) + ':' + makeTwoDigitNumber(date.getMinutes());
        }

        function setDateAndTime() {
            document.getElementById('date').value = getCurrentDate();
            document.getElementById('time').value = getCurrentTime();

        }


        function validateDate() {
            var date = document.getElementById('date').value;
            var year = parseInt(date.slice(0, 4));
            var month = parseInt(date.slice(5, 7));
            var day = parseInt(date.slice(8, 10));
            if (date > getCurrentDate() || year < 0 || day < 1 || day > 31 || month < 0 || month > 11 || date[4] != '-' || date[7] != '-') {
                setDateAndTime();
            }
        }

        function validateTime() {
            var time = document.getElementById('time').value;
            var hour = parseInt(date.slice(0, 2));
            var minute = parseInt(date.slice(3, 5));
            if (date > getCurrentTime() || hour > 24 || hour < 0 || minute > 59 || minute < 0 || time[7] != ':') {
                setDateAndTime();
            }
        }

        function addAttachment() {
            fileCount++;
            document.getElementById("number_of_files").value = fileCount;

            var br = document.createElement('br');
            var label = document.createElement('label');
            label.append('Załącznik ' + fileCount + ':');

            var input = document.createElement('input');
            input.type = 'file';
            input.name = 'file' + (fileCount - 1);
            var div = document.getElementById('files');
            div.append(br);
            div.append(label);
            div.append(input);
        }
    </script>

</head>

<body onload="setDateAndTime()">
    <form action="wpis.php" enctype="multipart/form-data" method="post">
        <label> Nazwa użytkownika: </label> <input type="text" name="user_name" /><br />
        <label> Hasło: </label> <input type="password" name="password" /><br />
        <label> Wpis: </label> <textarea name="wpis" rows="8" cols="64" method="post"></textarea><br />
        <label> Data: </label> <input type="text" name="date" id="date" onchange="validateDate()"><br />
        <label> Czas: </label> <input type="text" name="time" id="time" onchange="validateTime()"><br />
        <div id="files">
        </div>
        <button type="button" onclick="addAttachment()">Dodaj załącznik</button><br />
        <input type="submit" value="Wyślij" /><br />
        <input type="reset" value="Wyczyść formularz">
        <input type="hidden" id="number_of_files" name="number_of_files" value=0>
    </form>

    <?php
    include 'chat.html';
    include 'menu.php'; ?>

</body>

</html>