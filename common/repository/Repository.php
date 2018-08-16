<?php
namespace common\repository;

class Repository
{
    protected $apiData    = '';
    protected $apiCode    = 0;
    protected $apiMessage = '';
    protected $isApi = 1;

    protected function response(){
        if(!$this->apiData){
        	$this->apiData = new \stdClass();
        }
        
        $response['status']['success'] = $this->apiCode;
        $response['status']['message'] = $this->getError($this->apiMessage);
        $response['data'] = $this->apiData;
        $response['isApi'] = $this->isApi;

        return $response;
    }

    private function getError($errors){
    	if(is_array($errors)){
        	foreach ($errors as $key => $value) {
        		if(is_array($value)){
        			return $this->getError($value);
        		} else {
        			return $value;
        		}
        	}
        } else {
        	return $errors;
        }
    }
}