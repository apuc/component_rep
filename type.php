<?php
$dirs = scandir("/work/www/art-craft.tk/rep/components/");
unset($dirs[0]);
unset($dirs[1]);

$type = 'not found';

foreach($dirs as $dir) {
    if($dir == $_GET['slug']) {
        $manifest_json = file_get_contents("/work/www/art-craft.tk/rep/components/".$dir."/manifest.json");
        $manifest = json_decode($manifest_json);
        $type = $manifest->type;
        break;
    }
}

echo $type;