<?php
header('Content-type: text/plain');

$word = $_GET['word'];
$plik = fopen('dictionary.txt', 'r');

while (!feof($plik)) {
	$dict_entry = trim(fgets($plik));
	if (strlen($dict_entry) == strlen($word)) {
		$entry_array = preg_split('//u', $dict_entry, null, PREG_SPLIT_NO_EMPTY);
		$word_array = preg_split('//u', $word, null, PREG_SPLIT_NO_EMPTY);
		$match = true;

		for ($i = 0; $i<strlen($word); $i++) {
			if ($word_array[$i] != "_" && $word_array[$i] != $entry_array[$i]) {
				$match = false;
				break;			
			}
		}
		
		if ($match) {
			echo $dict_entry;
echo "<br/>";
		}
	}
}

fclose($plik);
?>
