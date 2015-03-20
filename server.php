<?php

//echo "Elspranicode development server started on http://localhost:8000\n";
//print "Elspranicode development server started on http://localhost:8000\n";
fwrite(STDOUT, "Kecik development server started on http://localhost:8000\n");

$cmd = "php -S localhost:8000 index.php";

$output = shell_exec($cmd);

/*if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
} else {
    exec($cmd . " > /dev/null &");
}*/
//echo "Elspranicode development server started on http://localhost:8000\n";
//print "Elspranicode development server started on http://localhost:8000\n";
//fwrite(STDOUT, "Elspranicode development server started on http://localhost:8000\n");

/*
while (true) {
	$post = $_POST;
	$get = $_GET;

	if ($post)
		print_r($post);

	if ($get)
		print_r($get);


	if ($_SERVER['HTTP_REFERER'])
		print_r($_SERVER['HTTP_REFERER']);

}
*/