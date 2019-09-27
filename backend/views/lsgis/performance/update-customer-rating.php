<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
use backend\models\Image;
$modelImage = new Image;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-customer-rating','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['update-customer-rating','id'=>$modelEvaluationConfigCustomerRating->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-formn edit-qa" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	    <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?= $form->field($modelEvaluationConfigCustomerRating, 'rating_value')->dropDownList([1=>'1',2=>'2',3=>'3',4=>'4',5=>'5'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?=$form->field($modelEvaluationConfigCustomerRating, 'performance_point')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
	<?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success pull-left']);
	?>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
   <?php
  $this->registerJs("
    $('#Pjax-edit-customer-rating').on('pjax:end', function() {
      $.pjax.reload({container:'#Pjax-add-customer-rating'});
    });

    ",View::POS_END);
?>
 <?php Pjax::end(); ?>