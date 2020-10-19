<?php

namespace workspace\controllers;

use core\App;
use core\Authorization;
use core\code_generator\CodeGeneratorController;
use core\component_manager\lib\CmService;
use core\component_manager\lib\Mod;
use core\component_manager\lib\RelationsHandler;
use core\Controller;
use core\Debug;
use core\ModulesHandler;
use Exception;
use workspace\modules\modules\models\Modules;
use workspace\modules\users\models\User;
use workspace\requests\LoginRequest;
use workspace\requests\RegistrationRequest;

class MainController extends Controller
{
    public function actionIndex()
    {
        $this->view->setTitle('Main Page');

        return $this->render('main/index.tpl', ['h1' => App::$config['app_name']]);
    }

    public function actionCodeGenerator()
    {
        $this->view->setTitle('CodeGen');

        $cg = new CodeGeneratorController();

        if (isset($_POST['table']) && isset($_POST['slug']) && isset($_POST['module']) && isset($_POST['model'])) {
            $info = $cg->genModule($_POST['table'], $_POST['slug'], $_POST['module'], $_POST['model']);

            $cm = new CmService();
            $manifest = json_decode(file_get_contents('workspace/modules/' . $_POST['module'] . '/manifest.json'));
            $data = ['version' => $manifest->version, 'status' => 'inactive', 'type' => 'module'];
            $cm->mod->save($_POST['module'], $data);
        }

        return $this->render('main/codegen.tpl', ['info' => (isset($info)) ? $info : '', 'tables' => $cg->getTables()]);
    }

    public function actionSignUp()
    {
        $this->view->setTitle('Sign Up');

        $request = new RegistrationRequest();

        if ($request->isPost() && $request->validate()) {
            $model = new User();
            $model->_save($request);

            $_SESSION['role'] = $model->role;
            $_SESSION['username'] = $model->username;

            $this->redirect('');
        }
        return $this->render('main/sign-up.tpl', ['errors' => $request->getMessagesArray()]);
    }

    public function actionSignIn()
    {
        $this->view->setTitle('Sign In');

        $mod = new Mod();
        if ($mod->getModInfo('users')['status'] != 'active') {
            $message = 'Чтобы сделать доступной регистрацию и авторизацию установите и активируйте модуль пользователей.';

            return $this->render('main/info.tpl', ['message' => $message]);
        } else {
            $request = new LoginRequest();
            if ($request->isPost() && $request->validate()) {
                $model = User::where('username', $request->username)->first();

                if (password_verify($request->password, $model->password_hash)) {
                    $_SESSION['role'] = $model->role;
                    $_SESSION['username'] = $model->username;

                    $this->redirect('');
                }
            }
            return $this->render('main/sign-in.tpl', ['errors' => $request->getMessagesArray()]);
        }
    }

    public function actionLogout()
    {
        session_destroy();
        $this->redirect('');
    }

    public function authentication()
    {
        $auth = new Authorization();
        $data = $auth->getBasicAuthData();
        $user = User::where('username', $data['username'])->first();

        return ($user && password_verify($data['password'], $user->password_hash)) ? json_encode(1) : json_encode(0);
    }

    public function remoteSignUp()
    {
        $model = new User();
        $model->username = $_POST['username'];
        $model->email = $_POST['email'];
        $model->role_id = 2;
        $model->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $model->save();

        return true;
    }

    public function serverModules()
    {
        return json_encode(ModulesHandler::getModel());
    }

    public function serverCore()
    {
        return json_encode((ModulesHandler::getCore()));
    }

    public function createManifest()
    {
        file_put_contents('modules.json', json_encode(ModulesHandler::getModel()));

        $this->redirect('admin/modules');
    }

    public function save()
    {
        $module = Modules::where('name', $_POST['slug'])->where('version', $_POST['version'])->first();

        if (!isset($module)) {
            ModulesHandler::write_to_db(json_decode(ModulesHandler::getModule()), 'INSERT');
        } else {
            $mod = new Mod();
            $mod->deleteDirectory("cloud/modules/" . $_POST['slug'] . "/" . $_POST['version']);
            ModulesHandler::write_to_db(json_decode(ModulesHandler::getModule()), 'UPDATE');
        }
    }

    public function relations()
    {
        $rel = new RelationsHandler();

        return json_encode($rel->arr($_POST['slug'], $_POST['version']));
    }
}