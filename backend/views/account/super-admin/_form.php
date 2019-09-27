<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Super Admin </h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
   <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Super Admin', 'url' => ['account/super-admin']];
if($modelAccount->id){
   $this->title =  $modelAccount->username;
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

        <?php 
    if($modelAccount->id){
    $form = ActiveForm::begin([
    'action' =>['account/update-super-admin','id'=>$modelAccount->id],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
    }
    else
      {
      $form = ActiveForm::begin([
    'action' =>['account/create-super-admin'],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
        }?>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'username')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
            <?php if(!$modelAccount->id){?>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'password_hash')->passwordInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Password');?>
                </div>
              </div>
            </div>
            <?php }?>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Email');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'first_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('First Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'middle_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Middle Name');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'last_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Last Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone1')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 1');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone2')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 2');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                    $genders= $modelPerson->getGender();
                    $listData=ArrayHelper::map($genders, 'id', 'name');
                    echo $form->field($modelPerson, 'fk_gender')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Gender')?>
                </div>
              </div>
           </div>
  </div>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
              <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
          </div>
          </div>
          </div>
        </div>
          <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
