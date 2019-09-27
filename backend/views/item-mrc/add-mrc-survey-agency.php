<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\Item;
use backend\models\GreenActionUnit;
use backend\models\Mrc;
use yii\web\View;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Sales Module</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Sales Module', 'url' => ['/item-mrc/sales-module']];
   $this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
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
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
    <?php  if($model->id){
    $form = ActiveForm::begin([
    'action' =>['item-mrc/update-mrc-survey-agency','id'=>$model->id],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
    }
    else
      {
      $form = ActiveForm::begin([
    'action' =>['item-mrc/add-mrc-survey-agency'],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
        }?>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $item= Item::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($item, 'id', 'name');
                     echo $form->field($model, 'item_id')->widget(Select2::classname(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
              ]);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $mrc= Mrc::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($mrc, 'id', 'name');
                     echo $form->field($model, 'mrc_id')->widget(Select2::classname(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
              ]);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($model, 'qty')->textInput(['maxlength' => true,'class'=>'form-control form-control-line quantity']);?>
                </div>
              </div>
        </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($model, 'rate_per_kg')->textInput(['maxlength' => true,'class'=>'form-control form-control-line rate']);?>
                </div>
              </div>
            </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($model, 'total')->textInput(['maxlength' => true,'class'=>'form-control form-control-line total']);?>
                </div>
              </div>
        </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($model, 'remarks')->textArea(['class'=>'form-control form-control-line']);?>
                </div>
              </div>
        </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>
        </div>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
<?php
 $this->registerJs("
$('.rate').on('change', function() {
 var rate = $('.rate').val();
 var quantity = $('.quantity').val();
 var total;
 total = rate * quantity;
 $('.total').val(total);
 });

 $('.quantity').on('change', function() {
 var quantity = $('.quantity').val();
 var rate = $('.rate').val();
 var total;
 total = rate * quantity;
 $('.total').val(total);
 });
",View::POS_END);