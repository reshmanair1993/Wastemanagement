<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\BuildingType;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Fee Collection Interval</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Fee Collection Interval', 'url' => ['/fee-collection-interval']];
   $this->title = Yii::t('app', 'Create Fee Collection Interval');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
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
    <?php $form = ActiveForm::begin();?>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
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
        <?php 
         $list= Buildingtype::getType();
         $listData=ArrayHelper::map($list, 'id', 'name');
        echo $form->field($model, 'building_type')->checkboxList($listData)->label('Building Types Available for Fee Collection Interval') ?>
        </div>
      </div>
    </div>
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
