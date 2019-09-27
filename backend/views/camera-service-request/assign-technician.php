<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Person;
use backend\models\Service;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	$form = ActiveForm::begin(['action' => ['add-technician','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>

	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
	                  <?php
	                  // $gt=Service::getGt();
										// print_r($model->camera_id);exit;

										$technician=$model->getTechnician($model->camera_id);
										// $technician = Person::find()
										// ->leftJoin('account','account.person_id=person.id')
										// ->select('account.id as id,person.first_name as first_name')
										// ->leftJoin('lsgi','lsgi.id=account.lsgi_id')
										// ->leftJoin('ward','ward.lsgi_id=lsgi.id')
										// ->leftJoin('camera','camera.ward_id=ward.id')
										// ->where(['camera.id'=>$model->camera_id])
										// ->all();
  	                $listData=ArrayHelper::map($technician, 'id', 'first_name');
										// print_r($modelCameraServiceAssignment);exit;

										echo $form->field($modelCameraServiceAssignment, 'account_id_technician')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->fkCameraServiceAssignment?$model->fkCameraServiceAssignment->account_id_technician:''])->label('Camera Technician');
	                // $technician=$model->getTechnician($model->camera_id);
	                // $listData=ArrayHelper::map($technician, 'id', 'first_name');
									// // print_r($listData);exit;
									// echo $form->field($modelCameraServiceAssignment, 'account_id_technician')->widget(Select2::classname(), [
									// 'data' => $listData,
									// 'language' => 'de',
									// 'options' => ['placeholder' => 'Select technician'],
									// // 'value'=> $model->fkCameraServiceAssignment?$model->fkCameraServiceAssignment->account_id_technician:''],
									// 'pluginOptions' => [
									// 'allowClear' => true
									// ],
									// ])->label('Camera Technician');

	                // echo $form->field($modelCameraServiceAssignment, 'account_id_technician')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->account_id_gt:''])->label('Camera Technician')
									?>
	    </div>
			</div>
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
					<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
					?>
				</div>
			</div>
    </div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
	</div>
	<?php
	 ActiveForm::end();
	?>
