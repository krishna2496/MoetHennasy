<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [  
        'request'=>[
            'csrfParam' => '_csrf-api',
            'class' => 'common\components\Request',
            'web'=> '/api/web',
            'adminUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ], 
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data = [
                        //'success' => $response->isSuccessful,
                        'code' => (isset($response->data['code']) && $response->data['code']) ? $response->data['code'] : $response->statusCode,
                        'message' => isset($response->data['message']) ? $response->data['message'] : '',
                        'data' => ($response->data !== true) ? $response->data : new stdClass(),
                    ];
                }
            },
            'format' =>  \yii\web\Response::FORMAT_JSON
        ],  
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/country',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
                'POST v1/site/login' => 'v1/site/login',
            ],        
        ]
    ],
    'params' => $params,
];



