<?php

namespace workspace\modules\users\controllers;

use core\App;
use core\Controller;
use core\Debug;
use workspace\modules\users\models\User;
use workspace\modules\users\requests\UsersSearchRequest;

class UsersController extends Controller
{
    protected function init()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) $this->redirect('');
        $this->viewPath = '/modules/users/views/';
        $this->layoutPath = App::$config['adminLayoutPath'];
        App::$breadcrumbs->addItem(['text' => 'AdminPanel', 'url' => 'adminlte']);
        App::$breadcrumbs->addItem(['text' => 'Users', 'url' => 'users']);
    }

    public function actionIndex()
    {
        $request = new UsersSearchRequest();
        $model = User::search($request);

        return $this->render('users/index.tpl',
            ['model' => $model, 'options' => $this->setOptions(), 'h1' => 'Пользователи']);
    }

    public function actionView($id)
    {
        $model = User::where('id', $id)->first();

        return $this->render('users/view.tpl', ['model' => $model, 'options' => $this->setOptions()]);
    }

    public function actionStore()
    {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
            $model = new User();
            $model->username = $_POST['username'];
            $model->email = $_POST['email'];
            $model->role = (int)$_POST['role'];
            $model->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $model->save();

            $this->redirect('admin/users');
        } else {
            return $this->render('users/store.tpl');
        }
    }

    public function actionEdit($id)
    {
        $model = User::where('id', $id)->first();

        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role'])) {
            $model->username = $_POST['username'];
            $model->email = $_POST['email'];
            $model->role = $_POST['role'];
            $model->save();

            $this->redirect('admin/users');
        } else
            return $this->render('users/edit.tpl', ['h1' => 'Редактировать: ', 'model' => $model]);
    }

    public function actionDelete($id)
    {
        User::where('id', $_POST['id'])->delete();
    }

    public function setOptions()
    {
        return [
            'serial' => '#',
            'fields' => [
                'username' => 'Логин',
                'email' => 'Email',
                'role_id' => 'Роль',
            ],
            'baseUri' => 'users',
            'pagination' => [
                'per_page' => 25,
            ],
        ];
    }
}