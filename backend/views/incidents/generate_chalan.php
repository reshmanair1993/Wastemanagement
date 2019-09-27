<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Camera */

$this->title = Yii::t('app', 'Create Chalan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cameras'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chalan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="chalan-form">

      <?php $form = ActiveForm::begin(['action' =>['generate-chalan','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-incident-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Name</label>
              <?= $form->field($modelGenerateChalan, 'name')->textInput(['class' => 'form-control','placeholder' => 'Name'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Email</label>
              <?= $form->field($modelGenerateChalan, 'email')->textInput(['class' => 'form-control','placeholder' => 'Email'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Address</label>
              <?= $form->field($modelGenerateChalan, 'address')->textInput(['class' => 'form-control','placeholder' => 'Address'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Subject</label>
              <?= $form->field($modelGenerateChalan, 'address')->textInput(['class' => 'form-control','placeholder' => 'Subject'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Description</label>
              <?= $form->field($modelGenerateChalan, 'description')->textInput(['class' => 'form-control','placeholder' => 'Description'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Amount</label>
              <?= $form->field($modelGenerateChalan, 'amount')->textInput(['class' => 'form-control','placeholder' => 'Amount'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 ta-right">
              <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button>
              <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
