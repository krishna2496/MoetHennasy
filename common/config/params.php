<?php
define('UPLOAD_PATH_USER_IMAGES', 'profile/');
define('UPLOAD_PATH_STORE_IMAGES', 'stores/');
define('UPLOAD_PATH_CATALOGUES_IMAGES','catalogues/');
define('BOTTLE','map/bottle.png');
define('API_KEY','AIzaSyAY16V1f859Ve4NZghFYEZ-XcAiHOIgdTw');
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
    'catalogue_status' => [0=>'Yes',1=>'No'], 
    'catalogue_status_inverse' => [0=>'Yes',1=>'No'], 
    'response_type' => ['drop-down' =>'Yes/No - Drop down','text' => 'Text'],
    'star_max_size' => [0 =>'10'],
    'star_min_size' => ['min_size' =>'3'],
    
];
