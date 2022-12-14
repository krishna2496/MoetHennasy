<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Permissions;
use common\helpers\CommonHelper; 

$this->title = 'Permission Matrix';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(CommonHelper::getPath('admin_url')."/js/permission-matrix.js",['depends' => [yii\web\JqueryAsset::className()]]);
$permissionColumnCounts = 0;
?>

<?php 

function genratePermissionTr($roleLabels, $roleLabels,$checkedValArray, $level = 0)
{
	$space = str_repeat(' &nbsp &nbsp ', $level);
	foreach ($roleLabels as $permission) {
		$blank = false;
		
		?>
		<tr >
			<td><?php echo $space.$permission['title']; ?></td>
				<?php 
				foreach ($roleLabels as $role) {
					if(!$blank){
						$inputValue = $role['id'].','.$permission['id'];
						$isChecked = (in_array($inputValue, $checkedValArray)) ? 'checked="checked"' : '' ;
						?>
						<td>
							<input class="role<?php echo $role['id']; ?>" name="permissionscheck[]" <?php echo $isChecked; ?> value="<?php echo $inputValue; ?>" type="checkbox">
						</td>
						<?php
					}else{
						?>
						<td>
						</td>
						<?php
					}
				}
				?>
		</tr>
		<?php

	}
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <div class="box-body">
				<form method="post" id="saveRolePermission" action="<?php echo CommonHelper::getPath('admin_url') ?>permission/parent-matrix">
					<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
					<?php
					if(CommonHelper::checkPermission('Admin.PermissionMatrix.Insert'))
					{
		             	?>
						<button class="btn btn-primary"><?php echo 'Save';?></button>
						</br />
						<?php
					}
					?>
					<div class="table-responsive">
						<table class="table table-hover gradienttable">
							<thead>
								<tr>
									<th><?php echo 'Permissions'; ?></th>
									<?php
									foreach ($roleLabels as $key=>$value)
									{
										?>
										<th>
											<div  class="checkbox checkbox-primary">
												<input type="checkbox" aria-invalid="false" onclick='checkboxClick(this);' name="roles[]" id="role<?=$value['id'];?>" value=<?=$value['id'];?> > 
												<label for="role<?=$value['id'];?>" class="label-remember"><?= $value['title'] ?></label>
											</div>
										</th>
										<?php
										$permissionColumnCounts++;
									}
									?>	
								</tr>
							</thead>
							<tbody>
								<?php genratePermissionTr($roleLabels, $roleLabels , $checkedValArray); ?>	
							</tbody>
						</table>
					</div>
					<?php
					if(CommonHelper::checkPermission('Admin.PermissionMatrix.Insert'))
					{
		             	?>
						<button class="btn btn-primary"><?php echo 'Save';?></button>
						</br />
						<?php
					}
					?>
				</form>
			</div>
		</div>
	</div>
</div>	

<style type="text/css">
.fixed_headers {
	width: 100%;
	table-layout: fixed;
	border-collapse: collapse;
}

.fixed_headers td{ width: <?php echo (85 / $permissionColumnCounts) ?>%;}
.fixed_headers th { width: <?php echo (85 / $permissionColumnCounts) ?>%;}
.fixed_headers td:nth-child(1) { width: 15% !important; }
.fixed_headers th:nth-child(1) { width: 15% !important; }

.fixed_headers tbody {
	display: block;
	overflow: auto;
	width: 100%;
	height: 600px;
}

.fixed_headers thead {
	display: block;
	overflow-y: scroll;
	width: 100%;
}

.fixed_headers thead tr{
	display: block;
	position: relative;
}
.permission-data-blank {
    background-color: #f5f5f5;
    color: #999999;
    font-size: 16px;
    font-weight: bold;
}
</style>

<script type="text/javascript">
	var totalPermissionCount = '<?php echo json_encode($permissionCount); ?>';
	var roleLabels = <?php echo json_encode($roleLabels); ?>;
</script>