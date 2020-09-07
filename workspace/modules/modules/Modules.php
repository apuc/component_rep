<?php

namespace workspace\modules\modules;


use core\App;

class Modules
{
    public static function run()
    {
        $config['adminLeftMenu'] = [
            [
                'title' => 'Modules',
                'url' => '/admin/modules',
                'icon' => '<i class="nav-icon fa fa-file"></i>',
            ],
        ];

        App::mergeConfig($config);
    }
}