<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;

class UploadRepository extends Repository
{
    public function store($data)
    {
        $uploadUrl = CommonHelper::getPath('upload_url');
        $this->apiCode = 0;
        if($data['files']){

            $this->apiCode = 1;
            $uploadedFile = [];
            foreach ($data['files'] as $key => $file) {
                $imageName = CommonHelper::uploadFile($file, $data['type']);
                $temp = array();
                $temp['path'] = $uploadUrl.$data['type'].'/'.$imageName;
                $temp['name'] = $imageName;
                $uploadedFile[] = $temp;
            }
            if(isset($data['type'])){
            if($data['type'] == 'store_config'){
              if(CommonHelper::resizeImage(UPLOAD_PATH_STORE_CONFIG_IMAGES.$temp['name'],$temp['name'],64,64,UPLOAD_PATH_STORE_CONFIG_IMAGES)){
                  
              }else{
                   $this->apiMessage = Yii::t('app', 'Something went wrong.');
              }
            }
            }
            
            $data = array();
            $data['uploadedFile'] = $uploadedFile;
            $this->apiData = $data;
        } else {
            $this->apiMessage = Yii::t('app', 'Something went wrong.');
        }

        return $this->response();
    }
}