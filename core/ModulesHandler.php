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

    public static function write_to_db($data, $action)
    {
        switch ($action) {
            case 'INSERT':
                $module = new Modules();
                $module->__save($data);
                break;
            case 'UPDATE':
                $module = Modules::where('name', $data->name)->where('version', $data->version)->first();
                $module->__save($data);
                break;
        }
    }

    public static function getCore()
    {
        return Modules::orderBy('name')->where('type', 'core')->get();
    }

    public static function getModel()
    {
        $server_modules = [];
        $modules = Modules::orderBy('name')->where('type', 'module')->get();
        foreach ($modules as $module) {
            $manifest = json_decode(file_get_contents(ROOT_DIR . "/cloud/modules/".$module['name']."/".$module['version']."/manifest.json"));
            $relations = (isset($manifest->relations)) ? $manifest->relations : '';
            array_push($server_modules,
                new \workspace\classes\Modules($module['name'], $module['version'], $module['description'], '', '',
                    'module', $relations, $module['created_at'], $module['updated_at']));
        }

        array_push($server_modules,
            new \workspace\classes\Modules('', '', '', '', '', '', '', '', ''));
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
                new \workspace\classes\Modules($value->name, $value->version, $value->description, '',
                    'server', 'module', $value->relations, $value->created_at, $value->updated_at));
        }

        return $model;
    }

    public static function getModule()
    {
        $slug = $_POST['slug'];
        $version = $_POST['version'];
        $modulesPath = "cloud/modules";

        if (!file_exists("$modulesPath/$slug"))
            mkdir("$modulesPath/$slug");
        if (!file_exists("$modulesPath/$slug/$version"))
            mkdir("$modulesPath/$slug/$version");
        if (!file_exists("$modulesPath/$slug/$version/$slug"))
            mkdir("$modulesPath/$slug/$version/$slug");

        $file = base64_decode($_POST['file']);

        file_put_contents(ROOT_DIR . "/$modulesPath/$slug/$version/$slug.zip", $file);

        $cm = new CmService();
        $cm->unpack("/$modulesPath/$slug/$version/$slug.zip", "/$modulesPath/$slug/$version", $slug);

        $manifest = file_get_contents(ROOT_DIR . "/$modulesPath/$slug/$version/$slug/manifest.json");
        file_put_contents(ROOT_DIR . "/$modulesPath/$slug/$version/manifest.json", $manifest);

        $mod = new Mod();
        $mod->deleteDirectory(ROOT_DIR . "/$modulesPath/$slug/$version/$slug");

        return $manifest;
    }

    public static function dataCore()
    {
        $version = $_POST['version'];
        $modulesPath = "cloud/core";

        if (!file_exists("$modulesPath/$version"))
            mkdir("$modulesPath/$version");
        if (!file_exists("$modulesPath/$version/core"))
            mkdir("$modulesPath/$version/core");

        $file = base64_decode($_POST['file']);

        file_put_contents(ROOT_DIR . "/$modulesPath/$version/core.zip", $file);

        $cm = new CmService();
        $cm->unpack("/$modulesPath/$version/core.zip", "/$modulesPath/$version/core", $version);

        $manifest = file_get_contents(ROOT_DIR . "/$modulesPath/$version/core/manifest.json");
        file_put_contents(ROOT_DIR . "/$modulesPath/$version/manifest.json", $manifest);

        $mod = new Mod();
        $mod->deleteDirectory(ROOT_DIR . "/$modulesPath/$version/core");

        return $manifest;
    }
}