<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
		'theme/bower_components/bootstrap/dist/css/bootstrap.min.css',
		'theme/bower_components/Ionicons/css/ionicons.min.css',
		'theme/dist/css/AdminLTE.min.css',
		'theme/plugins/iCheck/square/blue.css',
		'theme/dist/css/login.css',
    ];
    public $js = [
        'theme/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'theme/plugins/iCheck/icheck.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
