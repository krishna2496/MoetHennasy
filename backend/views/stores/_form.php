<?php
use common\helpers\CommonHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <div class="panel box box-primary">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            Store
                          </a>
                        </h4>
                      </div>
                      <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6"> 
                                    <?= $form->field($model, 'name')->textInput(['autofocus' => 'autofocus','maxlength' => true]) ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <?= $form->field($model, 'storeImage')->fileInput() ?>  
                                        </div>
                                        <?php if(isset($model->id) && $model->id) { ?>
                                        <div class="col-md-2">
                                            <img class="img-responsive" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_STORE_IMAGES . $model->photo); ?>"/>        
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                     <?= $form->field($model, 'market_id')->dropDownList($markets, ['prompt' => 'Select Market','class'=>'form-control select2']); ?> 
                                </div>
                                <div class="col-md-6">
                                     <?= $form->field($model, 'market_segment_id')->dropDownList(array(), ['prompt' => 'Select Cluster','class'=>'form-control select2']); ?> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'address1')->textarea(['rows' => 6]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'address2')->textarea(['rows' => 6]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                     <?= $form->field($model, 'country_id')->dropDownList($countries, ['prompt' => 'Select Country','class'=>'form-control select2']); ?> 
                                </div>
                                <div class="col-md-6">
                                     <?= $form->field($model, 'province_id')->dropDownList(array(), ['prompt' => 'Select Province','class'=>'form-control select2']); ?> 
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                     <?= $form->field($model, 'city_id')->dropDownList(array(), ['prompt' => 'Select City','class'=>'form-control select2']); ?> 
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'assign_to')->dropDownList(array(), ['prompt' => 'Select User','class'=>'form-control select2']); ?> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
                                </div>
                                <div class="col-md-6">
                                     <?= $form->field($model, 'grading')->dropDownList(yii::$app->params['store_grading'], ['prompt' => 'Select Grading','class'=>'form-control select2']); ?> 
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel box box-primary">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Store Manager
                          </a>
                        </h4>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'store_manager_first_name')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'store_manager_last_name')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'store_manager_phone_code')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'store_manager_phone_number')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'store_manager_email')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row" align="center">
            <div class="col-xs-6">
                <?php if(isset($model->id) && $model->id) { ?>
                    <?= Html::a('Cancel',  ['stores/index'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                <?php } else { ?>
                    <?= Html::a('Reset',  ['stores/create'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                <?php } ?>
            </div>
            <div class="col-xs-6">
              <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    function getMarketSegments(data){
        var str = "<option value>Select Cluster</option>";
        moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>stores/ajax-get-segment",data,'post').then(function(result){
            if(result.status.success == 1) {
                if(result.data.segments.length > 0) {
                    $.each(result.data.segments, function(key, value){
                        var selectedSegment = '';
                        if(value.marketSegment.id == '<?php echo $model->market_segment_id; ?>'){
                            var selectedSegment = 'selected';
                        }
                        str += "<option value="+value.marketSegment.id+" "+selectedSegment+">"+value.marketSegment.title+"</option>";
                    });
                }
            }
            $('#stores-market_segment_id').html(str);
        },function(result){
            alert('Fail');
        });
    }

    function getUsers(data){
        var userStr = "<option value>Select User</option>";
        moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>stores/ajax-get-user",data,'post').then(function(result){
            if(result.status.success == 1) {
                if(result.data.users.length > 0) {
                    $.each(result.data.users, function(key, value){
                        var selectedUser = '';
                        if(value.id == '<?php echo $model->assign_to; ?>'){
                            var selectedUser = 'selected';
                        }
                        userStr += "<option value="+value.id+" "+selectedUser+">"+value.first_name+" "+value.last_name+" - "+value.email+"</option>";
                    });
                }
            }
            $('#stores-assign_to').html(userStr);
        },function(result){
            alert('Fail');
        });
    }

    function getCities(data){
        var str = "<option value>Select City</option>";
        $('#stores-city_id').html(str);
        moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>stores/ajax-get-city",data,'post').then(function(result){
            if(result.status.success == 1) {
                if(result.data.cities.length > 0) {
                    $.each(result.data.cities, function(key, value){
                        var selectedCity = '';
                        if(value.id == '<?php echo $model->city_id; ?>'){
                            var selectedCity = 'selected';
                        }
                        str += "<option value="+value.id+" "+selectedCity+">"+value.name+"</option>";
                    });
                }
            }
            $('#stores-city_id').html(str);
        },function(result){
            alert('Fail');
        });
    }

    function getProvinces(data){
        var str = "<option value>Select Province</option>";
        moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>stores/ajax-get-province",data,'post').then(function(result){
            if(result.status.success == 1) {
                if(result.data.provinces.length > 0) {
                    $.each(result.data.provinces, function(key, value){
                        var selectedProvince = '';
                        if(value.id == '<?php echo $model->province_id; ?>'){
                            var selectedProvince = 'selected';
                        }
                        str += "<option value="+value.id+" "+selectedProvince+">"+value.name+"</option>";
                    });
                }
            }
            $('#stores-province_id').html(str);
        },function(result){
            alert('Fail');
        });
    }

    $("body").on("change", "#stores-market_id",function(event){
        var market_id = parseInt($('#stores-market_id').val());
        var data = {market_id : market_id};
        getMarketSegments(data);
        getUsers(data);
    });
    
    $("body").on("change", "#stores-country_id",function(event){
        var country_id = parseInt($('#stores-country_id').val());
        var data = {country_id : country_id};
           getProvinces(data);
       
    });

    $("body").on("change", "#stores-province_id",function(event){
        var province_id = parseInt($('#stores-province_id').val());
        var data = {province_id : province_id};
        getCities(data);
    });

    <?php if(isset($model->market_id) && $model->market_id) { ?>
        setTimeout(function(){
            getMarketSegments({market_id : parseInt('<?php echo $model->market_id;?>')});
            getUsers({market_id : parseInt('<?php echo $model->market_id;?>')});
        }, 3000);
    <?php } ?>

    <?php if(isset($model->country_id) && $model->country_id) { ?>
        setTimeout(function(){
            getProvinces({country_id : parseInt('<?php echo $model->country_id;?>')}); 
        }, 3000);
    <?php } ?>

    <?php if(isset($model->province_id) && $model->province_id) { ?>
        setTimeout(function(){
            getCities({province_id : parseInt('<?php echo $model->province_id;?>')});   
        }, 3000);
    <?php } ?>
</script>