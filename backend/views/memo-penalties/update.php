<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\MemoPenalty */

$this->title = 'Update Memo Penalty';
$this->params['breadcrumbs'][] = ['label' => 'Memo Penalties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
$modelUser = Yii::$app->user->identity;
$userRole  = $modelUser->role;
?>
<div class="memo-penalty-update">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Memo Penalties', 'url' => ['/memo-penalties/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memo Penalties'), 'url' => ['index']];
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

    <?php $form = ActiveForm::begin(['action' =>['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">MemoType</label>
          <?php
          $memoTypeList = ArrayHelper::map($modelMemoType,'id','name');
            echo $form->field($model, 'memo_type_id')->widget(Select2::classname(), [
            'data' => $memoTypeList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Memo Type'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false); ?>
        </div>
      </div>
      <?php if (!($userRole == 'admin-lsgi')){ ?>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Lsgi</label>
          <?php
          $lsgiList = ArrayHelper::map($modelLsgi,'id','name');
            echo $form->field($model, 'lsgi_id')->widget(Select2::classname(), [
            'data' => $lsgiList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Lsgi'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false); ?>
        </div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Amount</label>
          <?= $form->field($model, 'amount')->textInput(['class' => 'form-control','placeholder' => 'Amount'])->label(false); ?>
        </div>
      </div>
    <div class="row">
      <div class="col-lg-12 ta-right">
        <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
        <a href="<?=Url::to(['/memo-penalties/index'])?>" class="btn btn-success">cancel</a>
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
      </div>
    </div>

  </div>
  <div class="col-lg-4 col-md-4 col-sm-6 col-12">
  </div>
</div>
  <?php ActiveForm::end(); ?>

</div>
