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
    <h4 class="page-title"> Change Residential Association</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Residential Association', 'url' => ['residential-association/index']];
if($modelResidentialAssociation->id){
   $this->title =  $modelResidentialAssociation->username;
}
else
{
   $this->title =  'Change';
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

        <?php $form = ActiveForm::begin(['action' =>['change-association']]);?>
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                              <?php $associations= $modelResidentialAssociation->getAssociationslistCustomer();
                    $listData=ArrayHelper::map($associations, 'association_name', 'association_name');
                    echo $form->field($modelResidentialAssociation, 'from')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','name'=>'from'])?>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                              <?php $associations= $modelResidentialAssociation->getAssociationslistData();
                    $listData=ArrayHelper::map($associations, 'id', 'name');
                    echo $form->field($modelResidentialAssociation, 'to')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','name'=>'to'])?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                          <div class="col-sm-6 col-xs-6">
                          <?=Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-success']);?>
                          </div>
                        </div>
                      </div>
          <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
