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
        
        public static function sort_array_of_array(&$array, $subfield, $sort) {
            $sortarray = array();
            if(!empty($array) && (isset($array))){
            foreach ($array as $key => $row) {
                $sortarray[$key] = isset($row[$subfield]) ? $row[$subfield] : '';
            }
            }
            array_multisort($sortarray, $sort, $array);
        }
        
        public static function max_val(&$array, $subfield, $sort) {
            $sortarray = array();
            if(!empty($array) && (isset($array))){
            foreach ($array as $key => $row) {
                $sortarray[$key] = isset($row[$subfield]) ? $row[$subfield] : '';
            }
            }
        
            $max = 0;
            if($sortarray){
            $max = max($sortarray);
            $max++;
            }
            return $max;
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
				if(isset($value->permission->permission_label)){
					$permissionsLabelList[] = $value->permission->permission_label;
				}
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
	
    public static function exportFileAsCsv($file,$list)
	{	
		$data = '';
		foreach($list as $key=>$val)
		{
			$row = array();		
			foreach($val as $k=>$d)
			{		
				$row[$k] = '"'.$d.'"';
			}		
			$data .= join(',', $row)."\n"; 
		}
		// Output the headers to download the file
		header('Content-Type: text/csv');
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=$file");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $data;exit;
	}
        
    public static function resizeImage($fileuploadname, $saved_filname, $height, $width, $path , $min = true)
    {
        $file_path = CommonHelper::getPath('upload_path').$fileuploadname;
        
        $copyPath = CommonHelper::getPath('upload_path').$path.'original/';
        FileHelper::createDirectory($copyPath, $mode = 0775);
        copy($file_path , $copyPath.$saved_filname);

        $new_file_path = CommonHelper::getPath('upload_path').$path.$saved_filname;
        list($img_width, $img_height, $type, $attr) = getimagesize($file_path);
        if (!$img_width || !$img_height)
        {
            echo "Error";
        }

        $scale = min(
                $width / $img_width, $height / $img_height
        );

        if($min == false){
        	$scale = max(
                $width / $img_width, $height / $img_height
        	);
        }

        if ($scale > 1)
        {
            $scale = $height / $img_height;
        }
        $new_width = $img_width * $scale;
        $new_height = $img_height * $scale;

        $new_img = imagecreatetruecolor($new_width, $new_height);
        switch (strtolower(substr(strrchr($saved_filname, '.'), 1)))
        {
            case 'jpg':
            case 'jpeg':
                $src_img = imagecreatefromjpeg($file_path);
                $write_image = 'imagejpeg';
                break;
            case 'gif':
                $src_img = imagecreatefromgif($file_path);
                $write_image = 'imagegif';
                break;
            case 'png':
                $src_img = imagecreatefrompng($file_path);
                $write_image = 'imagepng';
                break;
            default:
                $src_img = $image_method = null;
        }
        $new_img = imagecreatetruecolor($new_width, $new_height);
        imagealphablending($new_img, false);
        imagesavealpha($new_img, true);
        $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
        imagefilledrectangle($new_img, 0, 0, $new_width, $new_height, $transparent);

        $success = $src_img && imagecopyresampled(
                        $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height
                ) && $write_image($new_img, $new_file_path);
        return $success;
    }
        
}

