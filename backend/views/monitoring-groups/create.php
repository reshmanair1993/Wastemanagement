<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\MonitoringGroup */

$this->title = Yii::t('app', 'Create Monitoring Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monitoring-group-create">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Monitoring Groups', 'url' => ['/monitoring-groups/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Groups'), 'url' => ['index']];
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

    <div class="group-form">

      <?php $form = ActiveForm::begin(['action' =>['create'],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-12">
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Group name</label>
                <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Group name'])->label(false); ?>
              </div>
            </div>
          </div>
        </div>

      <div class="row">
        <div class="col-lg-12 ta-right">
          <a href="<?=Url::to(['/monitoring-groups/index'])?>" class="btn btn-success">cancel</a>
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>

    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
