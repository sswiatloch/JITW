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

        if (!preg_match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$' ,$date))
        {
            
            $date = date('Y-m-d');
        }

        if (!preg_match('^[0-9]{2}:[0-9]{2}$' ,$time))
        {
            $time = date('H:i');
        }

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

            for ($i = 0; $i < 3; $i++) {
                $ext = pathinfo($_FILES['file' . $i]['name'], PATHINFO_EXTENSION);
                if ($ext != 'txt') {
                    continue;
                }
                $newname = $file_name . $i . '.' . $ext;
                $target = './blogs/' . $blog_name . '/' . $newname;
                move_uploaded_file($_FILES['file' . $i]['tmp_name'], $target);
            }

            header('Location: http://'.$_SERVER['HTTP_HOST'].'/~swiastan/projekt_php/blog.php?blog='.$blog_name);
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
		
		function setDateAndTime() {
			document.getElementById('date').value = getCurrentDate();
			document.getElementById('time').value = getCurrentTime();
			
		}

		function getCurrentDate() {
			var date = new Date();
			return date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
		}

		function getCurrentTime() {
			var date = new Date();
			return date.getHours() + ':' + date.getMinutes();
		}

		function validateDate() { //TODO dokoncz
			var date = document.getElementById('date').value;
			var year = parseInt(date.slice(0, 4));
			var month = parseInt(date.slice(5, 7));
			var day = parseInt(date.slice(8, 10));
			if (date > getCurrentDate() || year < 0 || day < 1 || day > 31 || month < 0 || month > 11) {
				setDateAndTime();
			}
		}

		function addAttachment() {
			fileCount++;
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
        <label> Czas: </label> <input type="text" name="time" id="time"><br />
		<div id="files">
		<button type="button" onclick="addAttachment()">Dodaj załącznik</button>
		</div>
        <input type="submit" value="Wyślij" /><br />
        <input type="reset" value="Wyczyść formularz">
    </form>

    <?php include 'menu.php'; ?>

</body>

</html>
