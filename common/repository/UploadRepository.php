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
        if($data['type'] && $data['files']){

            $this->apiCode = 1;
            $uploadedFile = [];
            foreach ($data['files'] as $key => $file) {
                $imageName = CommonHelper::uploadFile($file, $data['type']);
                $temp = array();
                $temp['path'] = $uploadUrl.$data['type'].'/'.$imageName;
                $temp['name'] = $imageName;
                $uploadedFile[] = $temp;
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