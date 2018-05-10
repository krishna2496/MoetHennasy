<?php
namespace common\helpers;
use Yii;
use yii\web\Session;
use yii\helpers\FileHelper;
use \yii\helpers\Url;
use yii\helpers\ArrayHelper;
use \yii\db\Expression;
use common\models\User;


class CommonHelper
{
	public static function getUser()
	{
		$currentUser = Yii::$app->user->identity;

		if(empty($currentUser)) {
	        return false;
	    }

        return $currentUser;
	}

	/**
	 * 
	 * @param type $file = Object for UploadedFile class
	 * @param type $path = extra path from upload directory
	 * @param string $fileName = [optional] specific name for uploaded file
	 * @return string|boolean = false if fail upload else return file name
	 */
	public static function uploadFile($file, $path = '', $fileName = '', $compress = false)
	{
		$path = trim(strip_tags($path));
		$fileName = trim(strip_tags($fileName));

		if($file !== null){
			//$basePath = \Yii::getAlias('@app');
			$basePath = self::getPath('root_path');
			$uploadPath = 'uploads/'.$path.'/';
			$fullPath = $basePath.$uploadPath;
			
			//for create dynamically folders for uploaded resume,coverletters according to job-Id while applynow job in front  
			FileHelper::createDirectory($fullPath, $mode = 0775);
			
			if($fileName == ''){
				$fileName = uniqid().'-'.rand(99,9999). '.' . $file->extension;
			}
			
			$fileName = str_replace(" ", "_", $fileName);
			
			$file->saveAs($fullPath.$fileName);

			if($compress){
				// Optimize image
				\Tinify\setKey('b3_4sPU_YI0vpBlDyaPaGkDn0NlGhpMW');

				$source = \Tinify\fromFile($fullPath.$fileName);
            	$source->toFile($fullPath.$fileName);
			}

			return $fileName;
		}else{
			return false;
		}
	}

	public static function getPath($type = ''){
		$return = '';

		switch ($type) {
			case 'base_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true)).'/';
				break;
				
			case 'site_url':
					$return = Url::base(true).'/';
				break;

			case 'current_url':
					$return = Url::to();
				break;

			case 'admin_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true));
					$return .= \Yii::$app->request->adminUrl.'/';
				break;

			case 'api_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true)).'/api/v1/';
				break;

			case 'api_admin_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true)).'/admin/';
				break;

			case 'front_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true)).'/';
				break;

			case 'upload_url':
					$return = str_replace(\Yii::$app->request->adminUrl, '', Url::base(true)).'/uploads/';
				break;

			case 'absolute_url':
					$return = Yii::$app->request->absoluteUrl;
				break;

			case 'root_path':
					$return = Yii::getAlias('@root').'/';
				break;

			case 'backend_path':
					$return = Yii::getAlias('@backend').'/';
				break;

			case 'front_path':
					$return = Yii::getAlias('@frontend').'/';
				break;

			case 'upload_path':
					$return = Yii::getAlias('@root').'/uploads/';
				break;
			
			default:
					$return = Url::base(true);
				break;
		}

		return $return;
	}
    
    public static function checkPermission($permission = '') {
		
    	$currentUser = Yii::$app->user->identity;

		// If user is super admin give all permissions
        $superAdminRole = Yii::$app->params['superAdminRole'];
        
        if(!empty($currentUser) && ($currentUser->role_id == $superAdminRole)) {
            return true;
        } elseif (!empty($currentUser) && $permission[0] == 'site.index' ) {
            return true;
        } elseif ($permission[0] == 'site.login' ) {
            return true;
        }

		$permissionsLabelList = array();
		if(isset($currentUser->permissions)){
			foreach ($currentUser->permissions as $key => $value) {
				$permissionsLabelList[] = $value->permission->permission_label;
			}
		}

		if(!is_array($permission)){
			$permission = array($permission);
		}

		if(!empty($permissionsLabelList) && !empty($permission)) {
			if(array_uintersect($permission, $permissionsLabelList, 'strcasecmp')) {
				return true;
			}
		}

		return false;
	}

	public static function getImage($path, $noImagePath = ''){
		$uploadUrl = CommonHelper::getPath('upload_url');
		$uploadPath = CommonHelper::getPath('upload_path');
		$imgUrl = $uploadUrl.'no-image.png';

		if(!empty($noImagePath)){
			$imgUrl = $uploadUrl.$noImagePath;
		}

		if(!empty($path) && file_exists($uploadPath.$path) && !is_dir($uploadPath.$path)){
			$imgUrl = $uploadUrl.$path;
		}
		return $imgUrl;
	}

	public static function getDropdown($list, $columns = array()){
    	// Prepare key, value
		$key   = '';
		$value = '';
		$columnArray = array(
			'key' => $columns[0], 
			'value' => $columns[1]
		);

		foreach ($columnArray as $arrKey => $arrValue) {
			if(is_array($arrValue)){
				${$arrKey} = function($data) use ($arrValue) {
					$clouserValue = array();
					foreach ($arrValue as $cKey => $cValue) {
						$clouserValue[] = $data[$cValue];
					}
		            return implode(' ', $clouserValue);
		        };		        
			} else {
				${$arrKey} = $arrValue;
			}
		}

		$lists = ArrayHelper::map($list, $key, $value);
		return $lists;
    }

    public static function generateRandomString($length = 5) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
        
    public static function geoMap($address1='',$address2 ='',$countryName='') {
	   
        set_time_limit(0);
        if(isset($address1) || isset($address2)){
            $url=urlencode($address1.','.$address2.','.$countryName);
        }
$requrl = "https://maps.googleapis.com/maps/api/geocode/json?address=$url&key=AIzaSyC7P6ddxuBfMcW2BZ9gxLTMhHSZXlZeZmU";
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_PROXY, '192.168.10.5:8080');
curl_setopt($ch, CURLOPT_URL, $requrl);
$response = trim(curl_exec($ch));
if (curl_errno($ch)) {
	return json_encode(array('status' => 'fail' , 'error' => curl_errno($ch), 'message' => curl_error($ch)));
}else if (trim($response) == '') {
	return json_encode(array('status' => 'fail' , 'error' => 404, 'message' => 'Invalid response'));
}
curl_close($ch);

$curl_response_results = json_decode($response,true);
return $curl_response_results;
}
        
}

