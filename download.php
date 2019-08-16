<?php
	//download.php
	//content type
	header('Content-type: text/plain');
	//open/save dialog box
	header('Content-Disposition: attachment; filename="Result.txt"');
	//read from server and write to buffer
	readfile('Result.txt');
?>