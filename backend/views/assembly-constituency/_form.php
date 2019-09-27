<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
    /* @var $this yii\web\View */
    /* @var $model backend\models\AssemblyContituency */
    /* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Assembly Constituency</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Assembly Constituency', 'url' => ['/assembly-constituency']];
   $this->title = Yii::t('app', 'Create Assembly Constituency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assembly Contituencies'), 'url' => ['index']];
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
            <?php $constituency= $model->getConstituency1();
              $listData=ArrayHelper::map($constituency, 'id', 'name');
                echo $form->field($model, 'parliament_constituency_id_1')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Parliament Constituency 1')?>
            </div>
           </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
              <?php
                  $districts= $model->getDistricts();
                  $listData=ArrayHelper::map($districts, 'id', 'name');
                  echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('District')
              ?>
            </div>
          </div>
       </div>
      <div class="col-sm-6 col-xs-6">
        <div class="form-group">
          <div class="col-sm-6 col-xs-6">
            <?php
              $constituency= $model->getConstituency2();
              $listData=ArrayHelper::map($constituency, 'id', 'name');
              echo $form->field($model, 'parliament_constituency_id_2')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Parliament Constituency 2')
            ?>
          </div>
      </div>
    </div>
    <div class="row">
    <div class="form-group">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
    </div>
    </div>

    <?php ActiveForm::end();?>

</div>
</div>
</div>
</div>
