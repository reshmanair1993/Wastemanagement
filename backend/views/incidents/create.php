<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Camera */

$this->title = Yii::t('app', 'Create Incident');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cameras'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="incident-form">

      <?php $form = ActiveForm::begin(['action' =>['create'],'options' => ['','data-pjax' => true,'class' => 'add-incident-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Incident Type</label>
              <?php
                $incidentTypesList = ArrayHelper::map($modelIncidentType,'id','name');
                echo $form->field($model, 'incident_type_id')->widget(Select2::classname(), [
                'data' => $incidentTypesList,
                'language' => 'de',
                'options' => ['placeholder' => 'Select Incident Type'],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ])->label(false);
              ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Camera</label>
              <?php
                $cameraList = ArrayHelper::map($modelCamera,'id','name');
                echo $form->field($model, 'camera_id')->widget(Select2::classname(), [
                'data' => $cameraList,
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
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Upload Image</label>
              <?php
              echo $form->field($modelImage, 'uploaded_files')->label(false)->fileInput();
              ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Upload Video</label>
              <?php
              echo $form->field($modelVideo, 'uploaded_files')->label(false)->fileInput();
              ?>
            </div>
          </div>
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
