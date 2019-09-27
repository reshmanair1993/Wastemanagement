<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\ParliamentConstituency */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Parliament Constituency</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Parliament Constituency', 'url' => ['/parliament-constituency']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parliament Constituency'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->name);
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
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

    <?php $form = ActiveForm::begin(); ?>
<div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
<div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
    <?= $form->field($model, 'constituency_type')->dropDownList([1=>'Rajya Sabha',2=>'Lok Sabha'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Constituency Type')?>
</div>
</div>
</div>
 <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
    <?php $states= $model->getStates();
              $listData=ArrayHelper::map($states, 'id', 'name');

              echo $form->field($model, 'state_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('State')?>
</div>
</div>
</div>
   <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
