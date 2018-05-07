<?php
define('UPLOAD_PATH_USER_IMAGES', 'profile/');
define('UPLOAD_PATH_STORE_IMAGES', 'stores/');
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'superAdminRole' => 1,
    'marketAdministratorRole' => 2,
    'salesManagerRole' => 3,
    'salesAgentsRole' => 4,
    'pageSize' => 10,
    'user.passwordResetTokenExpire' => 3600,
    'deviceType' => [
    	'ios' => 1,
    	'android' => 2,
    	'web' => 3
    ],
    'status' => [
        1 => 'Active',
        0 => 'Inactive'
    ],
    'limit' => [10 => 10, 20 => 20,50 => 50, 100 => 100 ,99999 => 'ALL'],  
];
