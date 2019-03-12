<?php

namespace backend\controllers;

use Yii;
use common\models\StoreConfiguration;
use common\models\StoreConfigurationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;
use common\models\CataloguesSearch;
use yii\filters\AccessControl;
use common\models\Stores;
use common\repository\BrandRepository;
use common\repository\UploadRepository;
use common\repository\StoreConfigRepository;
use common\repository\UserRepository;
use mPDF;
use common\components\Email;
use common\models\User;
use common\models\Questions;
use common\models\ConfigFeedback;
use common\models\Ratings;
use common\models\ShelfDisplay;

class ProductRuleController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                        [
                        'actions' => ['index', 'listing', 'update-config', 'delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                        [
                        'actions' => ['send-mail', 'feedback', 'create', 'view', 'review-store', 'save-image', 'update', 'save-image', 'save-data', 'save-product-data', 'modal-content', 'get-products', 'edit-products', 'save-config-data','delete-all'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    protected function fillUpEmptySpaceOfShelves(&$racksProductArray, $selvesWidth, $selevesCount) {
        $products = array();
        $arrayProducts = $racksProductArray;
        $selvesWidth = doubleval($selvesWidth);
        if ($this->ifRuleContain(\yii::$app->params['configArray']['market_share_count'])) {

            $productCount = count($racksProductArray);

            if ($productCount != 0) {
                for ($i = 0; $i < $selevesCount; $i++) {

                    $products = (isset($racksProductArray[$i]) && (!empty($racksProductArray[$i]))) ? $racksProductArray[$i] : '';

                    if ((empty($products)) && ($i > 0) && (isset($racksProductArray[$i - 1]))) {

                        $products = $racksProductArray[$i] = $racksProductArray[$i - 1];
                    }
                    $min = $sum = 0;
                    if (!empty($products)) {
                        $min = (min(array_column($products, 'width')) == 0) ? 1 : min(array_column($products, 'width'));
                        $sum = array_sum(array_column($products, 'width'));
                    }
                    $diff = intval($selvesWidth - $sum);
                    if (!empty($products)) {
                        if ($diff > $min) {
                            $sumOfMarketShare = array_sum(array_column($products, 'market_share'));
                            $sumOfMarketShare = ($sumOfMarketShare == 0) ? 1 : $sumOfMarketShare;
                            $noOfPlaces = intval(($selvesWidth) / ($min));

                            foreach ($products as $marketShareValue) {
                                $repeatCount = intval($marketShareValue['market_share'] * $noOfPlaces) / ($sumOfMarketShare);

                                for ($j = 0; $j < $repeatCount; $j++) {
                                    $tempSum = array_sum(array_column($racksProductArray[$i], 'width'));
                                    if ($selvesWidth >= ($tempSum + $marketShareValue['width'])) {
                                        array_push($racksProductArray[$i], $marketShareValue);
                                    }
                                }
                            }
                        }
                    }
                }
            }
         
            foreach ($racksProductArray as $key =>$value){
            $this->applySortingRule($racksProductArray[$key]);
            }
        }
    }

    protected function ruleTopShelf($dataValue, &$racksProductArray, $selvesWidth) {
       $sum = 0;
        if (!empty($racksProductArray)) {
            $sum = array_sum(array_column($racksProductArray, 'width'));
        }
        if ($selvesWidth >= ($sum + $dataValue['width'])) {
           
                if (intval($_SESSION['config']['depth_of_shelves']) >= intval($dataValue['length'])) {
                    $racksProductArray[$dataValue['id']] = $dataValue;
                }
            
        }
    }

    protected function applySortingRule(&$racksProductArray) {
//        echo '<pre>';
//        print_r($racksProductArray);exit;
        
//       $this->sort_array_of_array($racksProductArray, 'market_share', $sort);
        if ($this->ifRuleContain(\yii::$app->params['configArray']['market_share'])) {            
            $sort = SORT_DESC;
            $this->sort_array_of_array($racksProductArray, 'market_share', $sort);
        }
        if ($this->ifRuleContain(\yii::$app->params['configArray']['price'])) {
            $sort = SORT_ASC;
            $this->sort_array_of_array($racksProductArray, 'price', $sort);
        }
        if ($this->ifRuleContain(\yii::$app->params['configArray']['size_height'])) {
            $sort = SORT_ASC;
            $this->sort_array_of_array($racksProductArray, 'height', $sort);
        }
        if ($this->ifRuleContain(\yii::$app->params['configArray']['gift_box'])) {
            $giftProduct = $otherProduct = array();

            $skipBoxCheck = 0;
            foreach ($racksProductArray as $key => $value) {
                if(isset($value['box_only'])){ if ($value['box_only'] == 1) {
                    array_push($giftProduct, $value);
                } if ($value['box_only'] == 0) { array_push($otherProduct, $value); } }
                else {   $skipBoxCheck = 1; }
            }

            if(!$skipBoxCheck)
            {
                    $mergedArray = array_merge($giftProduct, $otherProduct);
                    $racksProductArray = $mergedArray;
            }
        }
        //product rule
         if ($this->ifRuleContain(\yii::$app->params['configArray']['order_product'])) {
          
                $this->sort_array_of_array($racksProductArray, 'reorder_id', SORT_ASC);
         }
     
    }

    public function sort_array_of_array(&$array, $subfield, $sort) {
        $sortarray = array();
        if(!empty($array) && (isset($array))){
        foreach ($array as $key => $row) {
            $sortarray[$key] = isset($row[$subfield]) ? $row[$subfield] : '';
        }
        }

        array_multisort($sortarray, $sort, $array);
    }

    protected function ifRuleContain($ruleValue) {
        $rulesArray = array();
        if (isset($_SESSION['config']['rules']) && !empty($_SESSION['config']['rules'])) {
            $rules = $_SESSION['config']['rules'];
            foreach ($rules as $key => $value) {
                $rulesArray[] = $value['product_fields'];
            }
        }
    
        if (in_array($ruleValue, $rulesArray)) {
            return true;
        } else {
            return false;
        }
    }

    protected function findModel($id) {
        if ($model = StoreConfiguration::findOne(['id'=>$id])) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function actionSendMail($thumb) 
    {
        $mpdf = new mPDF();
        $thumImage = explode('/', $thumb);
        $thumb = end($thumImage);

        $user = CommonHelper::getUser();

        $parentEmail = '';
        $userRepository = new UserRepository();
        if ($user['parent_user_id'] != '') {
            $fiterUser = array();
            $fiterUser['id'] = $user['parent_user_id'];
            $parentUser = $userRepository->userList();
            if ($parentUser['status']['success'] == 1) {

                $parentEmail = $parentUser['data']['users'][0]['email'];
                $parentFirstName = $parentUser['data']['users'][0]['first_name'];
                $parentLastName = $parentUser['data']['users'][0]['last_name'];
            }
        }
       
        $userEmail = $user['email'];
        $firstName = !empty($user['first_name']) ? $user['first_name'] : '';
        $lastName = !empty($user['last_name']) ? $user['last_name'] : '';
        $userId = !empty($user['id']) ? $user['id'] : '';
        $shelfImage = CommonHelper::getPath('upload_path') . UPLOAD_PATH_STORE_CONFIG_ORIGINAL_IMAGES . $thumb;

        $pdfFileName = CommonHelper::getPath('upload_path') . UPLOAD_PATH_STORE_CONFIG_PDF . Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')) . '' . $userId . '.pdf';

        $mpdf->WriteHTML($this->renderPartial('shelfPdf', ['image' => $shelfImage], true));
        $mpdf->Output($pdfFileName, 'F');
        
        $mail = new Email();
        $mail->email = $userEmail;
        $userString = array();
        $userString[] = $firstName;
        $userString[] = $lastName;
        $mail->body = $this->renderPartial('shelfMail');
        $mail->setFrom = Yii::$app->params['supportEmail'];
        $mail->subject = 'Store Shelf PDF';
        $mail->attachment = (Array($pdfFileName));
        $mail->set("NAME", implode(' ', $userString));
        $mail->send();
        if ($parentEmail != '') {
            $mail = new Email();
            $mail->email = $parentEmail;
            $userString = array();
            $userString[] = $parentFirstName;
            $userString[] = $parentLastName;
            $mail->body = $this->renderPartial('shelfMail');
            $mail->setFrom = Yii::$app->params['supportEmail'];
            $mail->subject = 'Store Shelf PDF';
            $mail->attachment = (Array($pdfFileName));
            $mail->set("NAME", implode(' ', $userString));
            $mail->send();
        }

        if (file_exists($pdfFileName)) 
        {
            @unlink($pdfFileName);
        }
    }

}
