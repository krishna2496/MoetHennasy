<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Moet Hennessy App',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request'=>[
            'csrfParam' => '_csrf-backend',
            'class' => 'common\components\Request',
            'web'=> '/backend/web',
            'adminUrl' => '/admin'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'market-segments/update/<id:\d+>' => 'market-segments/update',
                'market-segments/delete/<id:\d+>' => 'market-segments/delete',
                'configs/update-rating/<id:\d+>' => 'configs/update-rating',
                'market-segments/view/<id:\d+>' => 'market-segments/view',
                'product-categories/update/<id:\d+>' => 'product-categories/update',
                'product-categories/delete/<id:\d+>' => 'product-categories/delete',
                'product-varietal/update/<id:\d+>' => 'product-varietal/update',
                'product-varietal/delete/<id:\d+>' => 'product-varietal/delete',
                'help-categories/update/<id:\d+>' => 'help-categories/update',
                'help-categories/delete/<id:\d+>' => 'help-categories/delete',
                'help-categories/view/<id:\d+>' => 'help-categories/view',
                'market-contacts/index/<id:\d+>' => 'market-contacts/index',
                'store-configuration/listing/<id:\d+>' => 'store-configuration/listing',
                
                'product-types/index/<id:\d+>' => 'product-types/index',
                'product-types/view/<id:\d+>' => 'product-types/view',
                'product-types/update/<id:\d+>' => 'product-types/update',
                'product-types/delete/<id:\d+>' => 'product-types/delete',
                'store-configuration/modal-content/<id:\d+>' => 'store-configuration/modal-content',
                'store-configuration/review-store/<id:\d+>' => 'store-configuration/review-store/',
                'store-configuration/feedback/<id:\d+>' => 'store-configuration/feedback/',
                'market/rules/<id:\d+>' => 'market/rules',
                'rule-administration/product/<id:\d+>' => 'rule-administration/product',
                'apply/rules/<id:\d+>' => 'apply/rules',
            
                'apply/varientals' => 'apply/varientals',
               
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[^/]+>/<action:[^/]+>/<slug:[^/]+>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                
               
                
                'helps/update/<id:\d+>/<categoryId:\d+>' => 'helps/update',
                'helps/create/<id:\d+>' => 'helps/create',
                'catalogues/re-order/<id:\d+>' => 'catalogues/re-order',
                'brand/re-order/<id:\d+>' => 'brand/re-order',
                
                'helps/delete/<id:\d+>/<categoryId:\d+>' => 'helps/delete',
                'store-configuration/index/<id:\d+>/<categoryId:\d+>' => 'store-configuration/index',
                'store-configuration/delete/<id:\d+>/<storeId:\d+>' => 'store-configuration/delete',
                'helps/view/<id:\d+>/<categoryId:\d+>' => 'helps/view',
                'configs/update/<id:\d+>/<storeId:\d+>' => 'configs/update',
                'apply/brands/<brandId:\d+>/<id:\d+>' => 'apply/brands',
                
                'apply/modal-content/<marketId:\d+>/<categoryId:\d+>/<brandId:\d+>' => 'apply/modal-content',
                
                'configs/create/<id:\d+>' => 'configs/create',
              
              
                
                'configs/delete/<id:\d+>/<storeId:\d+>' => 'configs/delete',
                'configs/review/<id:\d+>/<storeId:\d+>' => 'configs/review',
                'configs/view/<id:\d+>/<storeId:\d+>' => 'configs/view',
                'users/update/<id:\d+>/<parentId:\d+>' => 'users/update',
                'users/view/<id:\d+>/<parentId:\d+>' => 'users/view',
                'users/delete/<id:\d+>/<parentId:\d+>' => 'users/delete',
                'help/index/<id:\d+>' => 'help/index',
              
                'market-contacts/index/<id:\d+>/<contactId:\d+>' => 'market-contacts/index',
                'market-contacts/view/<id:\d+>/<contactId:\d+>' => 'market-contacts/view',
                'market-contacts/delete/<id:\d+>/<contactId:\d+>' => 'market-contacts/delete',
               
                'store-configuration/update-config/<storeId:\d+>/<id:\d+>' => 'store-configuration/update-config',
                'store-configuration/delete/<storeId:\d+>/<id:\d+>' => 'store-configuration/delete',
              
               
             
               
               
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => '',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => '',
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@backend',
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'theme/bower_components/jquery/dist/jquery.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@backend/web/theme',
                    'js' => [
                        'bootstrap.js' => 'bower_components/bootstrap/dist/js/bootstrap.min.js'
                    ],
                    'css' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@backend/web/theme',
                    'js' => [
                        'bootstrap.js' => 'bower_components/bootstrap/dist/js/bootstrap.min.js'
                    ]
                ]
            ],
        ],
        'places' => [
            'class' => '\dosamigos\google\places\Places',
            'key' => 'AIzaSyA5AagLN2rL1WvX545cbKYJBJDQhkdwDZw',
            'format' => 'json' // or 'xml'
        ],
        'placesSearch' => [
            'class' => '\dosamigos\google\places\Search',
            'key' => 'AIzaSyA5AagLN2rL1WvX545cbKYJBJDQhkdwDZw',
            'format' => 'json' // or 'xml'
        ],
    ],
    'container' => [
        'definitions' => [
            'yii\widgets\LinkPager' => [
                'nextPageLabel' => 'Next',
                'prevPageLabel'  => 'Previous',
            ]
        ]
    ],
    'params' => $params,
];
