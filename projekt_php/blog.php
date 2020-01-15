<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Blog</title>
</head>

<body>
    <?php

    $all_blogs = array_diff(scandir('./blogs'), ['.', '..']);
    if (empty($_GET['blog'])) {
        if (empty($all_blogs)) {
            echo '<h1> Brak blogów do wyświetlenia! </h1>';
        } else {
            echo '<h1>Lista dostępnych blogów</h1><ul>';
            foreach ($all_blogs as $blog) {
                echo "<li><a href=\"http://charon.kis.agh.edu.pl/~swiastan/projekt_php/blog.php?blog=" . $blog . "\">" . $blog . "</a></li>";
            }
            echo '</ul>';
        }
    } else {
        $key = array_search($_GET['blog'], $all_blogs);

        if (!$key) {
            echo '<h1>Nie ma blogu o podanej nazwie!</h1>';
        } else {
            $blog = $all_blogs[$key];
            echo "<h1>" . $blog . "</h1>";

            $info = explode("\n", file_get_contents('./blogs/' . $blog . '/info'));
            echo "<h2>Autorstwa:</h2><p>" . $info[0] . "</p>";
            echo "<h2>Opis:</h2><p>" . $info[2] . "</p>";

            $wpisy = array_diff(scandir('./blogs/' . $blog), ['.', '..', 'info']);

            echo "<h2>Wpisy:</h2>";
            if (!empty($wpisy)) {
                foreach ($wpisy as $wpis) {
                    if (!strpos($wpis, '.')) {
                        $content = file_get_contents('./blogs/' . $blog . "/" . $wpis);

                        echo "<h3>" . $wpis . "</h3><p>" . $content . "</p>";

                        $files = array_diff(scandir('./blogs/' . $blog), ['.', '..', 'info']);
                        $attachments = array();
                        foreach ($files as $file) {
                            if (strpos($file, $wpis) === 0 && strpos($file, '.') === 17) {
                                array_push($attachments, $file);
                            }
                        }

                        if (!empty($attachments)) {
                            echo "<h4>Załączniki:</h4><ul>";
                            foreach ($attachments as $attachment) {
                                echo "<li><a href=\"http://charon.kis.agh.edu.pl/~swiastan/projekt_php/blogs/" . $blog . "/" . $attachment . "\">" . $attachment  . "</a></li>";
                            }
                            echo "</ul>";
                        }

                        echo "<h4>Komentarze:</h4>";

                        $comments = array_diff(scandir('./blogs/' . $blog . "/" . $wpis . ".k"), ['.', '..']);

                        foreach ($comments as $comment) {
                            $comment_content =  explode("\n", file_get_contents('./blogs/' . $blog . "/" . $wpis . ".k/" . $comment));
                            echo "<h5>(" . $comment_content[0] . ") " . $comment_content[2] . " " . $comment_content[1] . " napisał(a):</h5>";
                            echo "<p>" . $comment_content[3] . "</p>";
                        }

                        ?>
                        <form action="koment.php" method="post">
                            <input type="hidden" name="blog" value="<?php echo $blog; ?>" />
                            <input type="hidden" name="wpis" value="<?php echo $wpis; ?>" />
                            <input type="submit" value="Dodaj komentarz" />
                        </form>
    <?php

                    }
                }
            } else {
                echo "<p>Brak wpisów.</p>";
            }
        }
    }

    include 'menu.php';
    ?>

</body>

</html>