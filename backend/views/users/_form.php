<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
?>
<div class="permissions-create">
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
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'username')->textInput(['autofocus' => 'autofocus']) ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'email')->textInput() ?>
                		</div>
                	</div>
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'first_name')->textInput() ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'last_name')->textInput() ?>
                		</div>
                	</div>
                	<?php if(isset($model->id) && $model->id) { ?>
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'new_password')->passwordInput() ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'confirm_password')->passwordInput() ?>
                		</div>
                	</div>
                	<?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['status'], ['prompt' => 'Select Status']); ?> 
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'userImage')->fileInput() ?>  
                                </div>
                                <?php if(isset($model->id) && $model->id) { ?>
                                <div class="col-md-2">
                                    <img class="img-responsive" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_USER_IMAGES . $model->profile_photo); ?>"/>        
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'address')->textarea(['rows' => '6']); ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'phone')->textInput(); ?> 
                		</div>
                	</div>
                    <div class="row">
                        <div class="col-md-6">
                             <?= $form->field($model, 'market_id')->dropDownList($markets, ['prompt' => 'Select Market']); ?> 
                        </div>
                        <div class="col-md-6">
                             <?= $form->field($model, 'role_id')->dropDownList($roles, ['prompt' => 'Select User Type']); ?> 
                        </div>
                    </div>
                	<div class="row">
                		
                		<div class="col-md-6 <?php echo $parentUserClass; ?>" id="parentUser">
                			<?= $form->field($model, 'parent_user_id')->dropDownList($userList,['prompt' => 'Select Parent User']); ?>
                		</div>
                	</div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                	<?php if(isset($model->id) && $model->id) { ?>
                		<?= Html::a('Cancel',  ['users/index'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                	<?php } else { ?>
                		<?= Html::a('Reset',  ['users/create'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                	<?php } ?>
                </div>
                <div class="col-xs-6">
                  <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	$("body").on("change", "#user-role_id",function(event){
		$('#parentUser').hide();
		var role_id = parseInt($('#user-role_id').val());
		if(role_id == '<?php echo Yii::$app->params['marketAdministratorRole']; ?>') {
			return false;
		}

		var data = {role_id : role_id};
		<?php if(isset($model->id) && $model->id) { ?>
			data.update_id = parseInt('<?php echo $model->id; ?>');
		<?php } ?>
		
		var str = "<option value>Select Parent User</option>";
		moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>users/ajax-get-users",data,'post').then(function(result){
			if(result.status.success == 1) {
				if(result.data.users.length > 0) {
					$.each(result.data.users, function(key, value){
						str += "<option value="+value.id+">"+value.first_name+" "+value.last_name+"</option>";
					});
				}
			}
			$('#user-parent_user_id').html(str);
			$('#parentUser').show();
		},function(result){
			alert('Fail');
		});
	});
</script>
