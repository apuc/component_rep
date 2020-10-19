<?php

use core\App;

App::$collector->any('sign-up', ['workspace\controllers\MainController', 'actionSignUp']);
App::$collector->any('sign-in', ['workspace\controllers\MainController', 'actionSignIn']);
App::$collector->any('logout', ['workspace\controllers\MainController', 'actionLogout']);

App::$collector->any('check', ['workspace\controllers\MainController', 'check']);

App::$collector->cors('save', ['workspace\controllers\MainController'], ['save']);
App::$collector->cors('remote-sign-up', ['workspace\controllers\MainController'], ['remoteSignUp']);

App::$collector->any('get-core', ['workspace\controllers\MainController', 'serverCore']);
App::$collector->any('server-modules', ['workspace\controllers\MainController', 'serverModules']);
App::$collector->any('create-manifest', ['workspace\controllers\MainController', 'createManifest']);

App::$collector->any('authentication', ['workspace\controllers\MainController', 'authentication']);
App::$collector->any('relations', ['workspace\controllers\MainController', 'relations']);
App::$collector->any('codegen', ['workspace\controllers\MainController', 'actionCodeGenerator']);

App::$collector->group(['after' => 'main_group', 'params' => ['AFTER']], function($router) {
    App::$collector->group(['before' => 'next'], function($router) {
        App::$collector->get('/', [workspace\controllers\MainController::class, 'actionIndex'], ['before' => 'some', 'params' => ['param to some, BEFORE']]);
    });
});