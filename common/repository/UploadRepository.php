<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;

class UploadRepository extends Repository
{
    public function store($data)
    {
        $this->apiCode = 0;
        if($data['type'] && $data['files']){

            $this->apiCode = 1;
            $uploadedFile = [];
            foreach ($data['files'] as $key => $file) {
                //$uploadedFile[$file->name] = CommonHelper::uploadFile($file, $data['type']);
                $uploadedFile[] = CommonHelper::uploadFile($file, $data['type']);
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