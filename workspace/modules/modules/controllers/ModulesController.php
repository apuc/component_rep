<?php


namespace workspace\modules\modules\controllers;


use core\App;
use core\component_manager\lib\Mod;
use core\Controller;
use core\Debug;
use workspace\modules\modules\models\Modules;
use workspace\modules\modules\requests\ModulesSearchRequest;

class ModulesController extends Controller
{
    protected function init()
    {
        $this->viewPath = '/modules/modules/views/';
        $this->layoutPath = App::$config['adminLayoutPath'];
        App::$breadcrumbs->addItem(['text' => 'AdminPanel', 'url' => 'adminlte']);
        App::$breadcrumbs->addItem(['text' => 'Modules', 'url' => 'admin/modules']);
    }

    public function actionIndex()
    {
        $request = new ModulesSearchRequest();
        $request->type = 'module';
        $model = Modules::search($request);

        $options = $this->setOptions();

        return $this->render('modules/index.tpl', ['h1' => 'Modules', 'model' => $model, 'options' => $options]);
    }

    public function actionView($id)
    {
        $model = Modules::where('id', $id)->first();

        $options = $this->setOptions();

        return $this->render('modules/view.tpl', ['model' => $model, 'options' => $options]);
    }

    public function actionStore()
    {
        if($this->validation()) {
            $model = new Modules();
            $model->_save();

            $this->redirect('admin/modules');
        } else
            return $this->render('modules/store.tpl', ['h1' => 'Добавить']);
    }

    public function actionEdit($id)
    {
        $model = Modules::where('id', $id)->first();

        if($this->validation()) {
            $model->_save();

            $this->redirect('admin/modules');
        } else
            return $this->render('modules/edit.tpl', ['h1' => 'Редактировать: ', 'model' => $model]);
    }

    public function actionDelete()
    {
        $module = Modules::where('id', $_POST['id'])->first();
        $mod  = new Mod();
        $name = $module->name;
        $version = $module->version;
        $mod->deleteDirectory("cloud/modules/$name/$version");

        Modules::where('id', $_POST['id'])->delete();

        $other_modules = Modules::where('name', $name)->get();
        if(empty($other_modules))
            $mod->deleteDirectory("cloud/modules/$name");
    }

    public function setOptions()
    {
        return [
            'serial' => '#',
            'fields' => [
                'name' => 'Name',
                'version' => 'Version',
                'description' => 'Description',
                'user_id' => 'User_id',
            ],
            'baseUri' => 'modules'
        ];
   }

   public function validation()
   {
       return (isset($_POST["name"]) && isset($_POST["version"]) && isset($_POST["type"]) && isset($_POST["description"]) && isset($_POST["user_id"])) ? true : false;
   }
}