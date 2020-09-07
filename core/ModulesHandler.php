<?php


namespace core;


use core\component_manager\lib\CmService;
use core\component_manager\lib\Mod;
use workspace\modules\modules\models\Modules;

class ModulesHandler
{
    public static function check()
    {
        $module = Modules::where('name', $_POST['slug'])->where('version', $_POST['version'])->first();

        return (isset($module)) ? 0 : 1;
    }

    public static function db($data, $action)
    {
        switch ($action) {
            case 'INSERT':
                $module = new Modules();
                $module->__save($data);
                break;
            case 'UPDATE':
                $module = Modules::where('name', $data['manifest']->name)->where('version', $data['manifest']->version)->first();
                $module->__save($data);
                break;
        }
    }

    public static function getModel()
    {
        $server_modules = [];
        $modules = Modules::orderBy('name')->get();
        foreach ($modules as $module)
            array_push($server_modules,
                new \workspace\classes\Modules($module->name, $module->version, $module->description, '', ''));

        array_push($server_modules,
            new \workspace\classes\Modules('', '', '', '', ''));
        $temp = $server_modules[0]->name;

        $model_arr = [];
        $model = [];
        foreach ($server_modules as $value) {
            if ($temp != $value->name) {
                usort($model_arr, function ($a, $b) {
                    return strcmp($b->version, $a->version);
                });
                array_push($model, $model_arr);
                $temp = $value->name;
                $model_arr = [];
            }
            array_push($model_arr,
                new \workspace\classes\Modules($value->name, $value->version, $value->description, '', 'server'));
        }
        return $model;
    }

    public static function getModule()
    {
        $slug = $_POST['slug'];
        $version = $_POST['version'];
        $filename = "$slug.zip";
        $modulesPath = "cloud/modules";
        $cm = new CmService();
        $mod = new Mod();

        mkdir("$modulesPath/$slug");
        mkdir("$modulesPath/$slug/$version");
        mkdir("$modulesPath/$slug/$version/$slug");

        $file = file_get_contents($_POST['file']);
        file_put_contents("$modulesPath/$slug/$version/$filename", $file);

        $cm->unpack("/$modulesPath/$slug/$version/$filename", "/$modulesPath/$slug/$version", $slug);

        $manifest = file_get_contents("$modulesPath/$slug/$version/$slug/manifest.json");
        file_put_contents("$modulesPath/$slug/$version/manifest.json", $manifest);

        $mod->deleteDirectory("$modulesPath/$slug/$version/$slug");

        return $manifest;
    }
}