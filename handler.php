<?php
header('Content-Type: application/json');

class Data 
{
	public $name;
	public $type;
	public $version;
	public $description;

	public function __construct($name, $type, $version, $description)
	{
		$this->name = $name;
		$this->type = $type;
		$this->version = $version;
		$this->description = $description;		
	}
}

$dirs = scandir("/work/www/art-craft.tk/rep/components/");
unset($dirs[0]);
unset($dirs[1]);

$res = array();
foreach($dirs as $dir) {
	$manifest_json = file_get_contents("/work/www/art-craft.tk/rep/components/".$dir."/manifest.json");
	$manifest = json_decode($manifest_json);
	$data = new Data($manifest->name, $manifest->type, $manifest->version, $manifest->description);
	array_push($res, $data);
}

echo json_encode($res);