<?php
define('UPLOAD_PATH_USER_IMAGES', 'profile/');
define('UPLOAD_PATH_STORE_IMAGES', 'stores/');
define('UPLOAD_PATH_CATALOGUES_IMAGES','catalogues/');
define('UPLOAD_PATH_BRANDS_IMAGES','brands/');
define('UPLOAD_PATH_RULES_IMAGES','rules/');
define('UPLOAD_PATH_STORE_CONFIG_IMAGES','store_config/');
define('UPLOAD_PATH_STORE_CONFIG_ORIGINAL_IMAGES','store_config/original/');
define('UPLOAD_PATH_STORE_CONFIG_PDF','store_config/pdf/');

define('BOTTLE','map/bottle.png');
define('UPLOAD_PATH_IMAGES','images/');
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
    'catalogue_status' => [1=>'Yes',0=>'No'], 
    'catalogue_status_inverse' => [1=>'Yes',0=>'No'], 
    'response_type' => ['drop-down' =>'Yes/No'],
    'star_max_size' => [0 =>'3'],
    'star_min_size' => ['min_size' =>'1'],
    'store_grading' => ['tier_1' => 'Tier 1' ,'tier_2'=> 'Tier 2' ,'tier_3' => 'Tier 3'],
    'num_of_shelves' => ['min' => '0','max' => '50'],
    'height_of_shelves' => ['min' => '0','max' => '450'],
    'width_of_shelves' => ['min' => '0','max' => '500'],
    'depth_of_shelves' => ['min' => '0','max' => '100'],
    'shelfConfig' => ['0' => 100,'1' =>2,'3' =>30],
    'rackWidth' => ['0' => 550],
    'configArray' => ['top_shelf' => 'top_shelf','market_share' =>'market_share_left_right','size_height' => 'size_height' , 'price' => 'price' , 'gift_box' => 'gift_box','market_share_count'=>'market_shares'],
    'defaultSorting' => SORT_ASC
    ];
