<?php
fwrite(STDOUT, "Kecik development server started on http://localhost:8000\n");

$cmd = "php -S localhost:8000 -t .";

$output = shell_exec($cmd);

/*if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
} else {
    exec($cmd . " > /dev/null &");
}*/
// fwrite(STDOUT, "Kecik development server started on http://localhost:8000\n");
