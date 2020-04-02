<?php
header("content-type: image/jpg");

$dirs = scandir("/work/www/art-craft.tk/rep/components/");
unset($dirs[0]);
unset($dirs[1]);

$img = 'not found';

foreach($dirs as $dir) {
    if($dir == $_GET['slug']) {
        $img = file_get_contents("/work/www/art-craft.tk/rep/components/".$dir."/preview.jpg");
        break;
    }
}

echo $img;