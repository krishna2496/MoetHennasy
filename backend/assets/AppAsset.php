<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/font-awesome.min.css',
        'theme/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'theme/bower_components/Ionicons/css/ionicons.min.css',
        'theme/bower_components/select2/dist/css/select2.min.css',
        'theme/dist/css/AdminLTE.min.css',
        'theme/plugins/iCheck/square/blue.css',
        'theme/dist/css/skins/_all-skins.min.css',
        'theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'theme/bower_components/bootstrap-daterangepicker/daterangepicker.css',
    ];
    public $js = [
        'js/jquery.cookie.js',
        'js/custom.js',
        'theme/bower_components/jquery-ui/jquery-ui.min.js',
        'theme/bower_components/select2/dist/js/select2.full.min.js',
        'theme/bower_components/ckeditor/ckeditor.js',
        'theme/bower_components/moment/min/moment.min.js',
        'theme/bower_components/bootstrap-daterangepicker/daterangepicker.js',
        'theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'theme/plugins/iCheck/icheck.min.js',
        'theme/dist/js/adminlte.min.js',
        'theme/dist/js/demo.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
