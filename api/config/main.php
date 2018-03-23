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
                if (!isset($response->data['isApi'])) {
                    $response->data = [
                        'status'=> [
                            'success' => $response->isSuccessful ? 1 : 0,
                            'message' => $response->data['message'],
                        ],
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                } else {
                    unset($response->data['isApi']);
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@api/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'mytest225@gmail.com',
                'password' => 'tatva123',
                'port' => '587',//587,465,
                'encryption' => 'tls',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST v1/site/login' => 'v1/site/login',
                'POST v1/site/request-password-reset' => 'v1/site/request-password-reset',
                'POST v1/site/reset-password' => 'v1/site/reset-password',
                'POST v1/site/logout' => 'v1/site/logout',
                'POST v1/site/update-device-token' => 'v1/site/update-device-token',
            ],        
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@api/messages',
                    'sourceLanguage' => '',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@api/messages',
                    'sourceLanguage' => '',
                ],
            ],
        ],
    ],
    'params' => $params,
];



