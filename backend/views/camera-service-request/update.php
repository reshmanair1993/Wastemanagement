<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\CameraServiceRequest */

$this->title = 'Update Camera Service Request';
$this->params['breadcrumbs'][] = ['label' => 'Camera Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="camera-service-request-update">
  <?php $form = ActiveForm::begin(['action' =>['update','id' =>$model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Camera</label>
          <?php $camerasList = ArrayHelper::map($modelCamera,'id','name'); ?>
          <?php
            echo $form->field($model, 'camera_id')->widget(Select2::classname(), [
            'data' => $camerasList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Camera'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false);
          ?>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Camera Services</label>
          <?php $cameraServicesList = ArrayHelper::map($modelService,'id','name'); ?>
          <?php
            echo $form->field($model, 'service_id')->widget(Select2::classname(), [
            'data' => $cameraServicesList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Camera'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false);
          ?>

        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 ta-right">
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <a href="<?=Url::to(['/camera-service-request/index'])?>" class="btn btn-success">Cancel</a>
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-6 col-12">
</div>
</div>
<?php ActiveForm::end(); ?>

</div>
