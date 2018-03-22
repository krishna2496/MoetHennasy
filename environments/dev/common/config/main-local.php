<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.10.4;dbname=team1_moet_hennessy_app',
            'username' => 'team1',
            'password' => "d^'7 }1[P",
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,//false it while testing email
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'mytest225@gmail.com',
                'password' => 'tatva123',
                'port' => '587',//587,465,
                'encryption' => 'tls',
            ],
        ],
    ],
];
