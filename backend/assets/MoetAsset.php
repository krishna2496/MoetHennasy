<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MoetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
	'js/moet.js',
	//'theme/dist/js/bootstrap-wizard.min.js',
	//'theme/dist/js/modernizr.min.js',
	 //'theme/dist/js/lodash.js',
	 //'theme/dist/js/fabric.js',
	 //'theme/dist/js/myCanvas.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
