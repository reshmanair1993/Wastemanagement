<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
foreach($params as $param => $val)
  ${$param} = $val;


?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Installation Checklist</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Installation Checklist', 'url' => ['installation-checklist/index']];
if($modelCameraInstallationCheckList->id){
   $this->title =  $modelCameraInstallationCheckList->name;
}
else
{
   $this->title =  'Update';
}
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

        <?php $form = ActiveForm::begin(['action' =>['update','id'=>$modelCameraInstallationCheckList->id]]);?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?=$form->field($modelCameraInstallationCheckList, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Association name');?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?=$form->field($modelImage, 'uploaded_files')->fileInput(['value'=>'','maxlength' => true,'class'=>'form-control form-control-line'])->label('Image');?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']);?>
            </div>
          </div>
        </div>
          <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
<?php

$title = isset($title)?$title:'Success';
$type = isset($type)?$type:'success';
$message = isset($message)?$message:'Installation Checklist has been added successfully';
$title = Html::encode(trim($title));
$message = Html::encode(trim($message));
$title =  $title;
$message =  $message;
if (isset($saved) && $saved == 1):
  $this->registerJs("
  swal({title:'Success',text: '$message', type:'$type'});
  $.pjax.reload('#pjax-camera-installation-list');
  ");
endif ;

?>
