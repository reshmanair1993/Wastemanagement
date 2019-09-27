<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\CameraServiceRequest */

$this->title = 'Update Camera Service';
$this->params['breadcrumbs'][] = ['label' => 'Camera Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="camera-service-request-update">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
    <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
    <?php
    $breadcrumb[]  = ['label' => 'Camera Service', 'url' => ['/camera-service/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camera Service'), 'url' => ['index']];
    if($model->id){
    $this->title =  'Update';
    }
    else
    {
    $this->title =  'Create';
    }
    $breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
    ?>
    <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
    </div>
  </div>
  <?php $form = ActiveForm::begin(['action' =>['update','id' =>$model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Service Name</label>
          <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Service Name'])->label(false); ?>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Sort order</label>
          <?= $form->field($model, 'sort_order')->textInput(['class' => 'form-control','placeholder' => 'Sort order'])->label(false); ?>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Upload Image</label>
          <?php
          echo $form->field($modelImage, 'uploaded_files')->label(false)->fileInput();
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 ta-right">
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <a href="<?=Url::to(['/camera-service/index'])?>" class="btn btn-success">Cancel</a>
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-6 col-12">
</div>
</div>
<?php ActiveForm::end(); ?>


</div>
