<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Customer;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
$modelImage = new Image;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-gt','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-gt','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
         <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                  $gt = Customer::getGtList($model->fkAccount->fkCustomer->ward_id,$model->account_id_customer);
                  // $gt=$model->getGt($model->account_id_customer);
                $listData=ArrayHelper::map($gt, 'id', 'first_name');
								// print_r($listData);exit;
                echo $form->field($modelServiceAssignment, 'account_id_gt')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->account_id_gt:''])->label('Green Technician')?>
                </div>
              </div>
            </div>
	<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"></div>
	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
	<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
 <?php Pjax::end(); ?>
