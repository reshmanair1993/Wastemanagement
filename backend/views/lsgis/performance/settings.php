<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-lsgi-settings','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-lsgi-settings','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;width: 1219px;
    margin-left: -28px;">
         <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'service_assigment_expiry_hours')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Service Assigment Expiry In Days');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                     <?=$form->field($model, 'rating_calculation_interval_hours')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Rating Calculation Interval In Days');?>
                </div>
            </div>
        </div> 
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                     <?=$form->field($model, 'complaints_count_rating_calculation_interval_hours')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Complaints Count Rating Calculation Interval In Days');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                     <?=$form->field($model, 'default_service_point')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Default Service Performance Point');?>
                </div>
            </div>
        </div>
	<div class="col-sm-3 col-xs-3">
            <div class="form-group">
                <div class="col-sm-3 col-xs-3">
	<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	</div>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
 <?php Pjax::end(); ?>