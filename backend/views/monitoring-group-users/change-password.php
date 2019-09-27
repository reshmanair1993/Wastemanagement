<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\MonitoringGroupUser */

$this->title = Yii::t('app', 'Change Password', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Group Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="monitoring-group-user-update">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Monitoring Group Users', 'url' => ['/monitoring-group-users/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Group Users'), 'url' => ['index']];
  if($model->id){
     $this->title =  'Change Password';
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
  <?php $form = ActiveForm::begin(['action' =>['change-password','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
  <div class="col-lg-4 col-md-4 col-sm-6 col-12">
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6 col-12">


  <div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
      <label for="eh-first-name">Email</label>
      <?= $form->field($modelPerson, 'email')->textInput(['class' => 'form-control','placeholder' => 'Email'])->label(false); ?>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
      <label for="eh-first-name">New Password</label>
      <?= $form->field($model, 'password_hash')->passwordInput(['class' => 'form-control','placeholder' => 'Password'])->label(false); ?>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12">
      <label for="eh-first-name">Re-type Password</label>
      <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-control','placeholder' => 'Re-type Password'])->label(false); ?>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12">
      <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>
  </div>
  </div>
  <?php ActiveForm::end(); ?>

</div>
