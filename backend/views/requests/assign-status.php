<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
$modelImage = new Image;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-gt','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-status','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => true,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
         <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php 
                  // $gt=Service::getGt();
                  $status=$model->getStatusOption($model->service_id);
                $listData=ArrayHelper::map($status, 'id', 'value'); 
                echo $form->field($modelServiceAssignment, 'servicing_status_option_id')->dropDownList($listData,['prompt' => 'Choose Option','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->servicing_status_option_id:''])->label('Status Option')?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'remarks')->textArea(['rows' => 10,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->remarks:'']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'lat_update_from')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:''])->label('Latitude');?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'lng_updated_from')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:''])->label('Longitude');?>
                </div>
            </div>
        </div>
    <?php if($model->fkService->ask_waste_quality):?>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $quality=$model->getQuality();
                $listData=ArrayHelper::map($quality, 'id', 'name'); 
                echo $form->field($modelServiceAssignment, 'quality')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->quality:''])->label('Quality')?>
                </div>
            </div>
        </div>
        <?php endif;?>
    <?php if($model->fkService->ask_waste_quality):?>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'quantity')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->quantity:''])->label('Quantity');?>
                </div>
            </div>
        </div>
    <?php endif;?>
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