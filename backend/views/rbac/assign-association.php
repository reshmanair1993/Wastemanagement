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
foreach($params as $param => $val)
  ${$param} = $val;


?>
<h3>Assign Association to role</h3>
<br>
<?php $form = ActiveForm::begin(['action' =>['assign-association','name'=>$name]]);?>
<?php
$roleAssociation = \backend\models\RoleAssociation::find()->where(['role'=>$name])->one();
?>
<div class="row">
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_lsgi_association == 1){
        $modelRoleAssociation->has_lsgi_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_lsgi_association')->checkbox()->label(false); ?>
  </div>
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_ward_association == 1){
        $modelRoleAssociation->has_ward_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_ward_association')->checkbox()->label(false); ?>
  </div>
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_hks_association == 1){
        $modelRoleAssociation->has_hks_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_hks_association')->checkbox()->label(false); ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_gt_association == 1){
        $modelRoleAssociation->has_gt_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_gt_association')->checkbox()->label(false); ?>
  </div>
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_survey_agency_association == 1){
        $modelRoleAssociation->has_survey_agency_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_survey_agency_association')->checkbox()->label(false); ?>
  </div>
  <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->district_association == 1){
        $modelRoleAssociation->district_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'district_association')->checkbox()->label(false); ?>
  </div>
   <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_supervisor_association == 1){
        $modelRoleAssociation->has_supervisor_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_supervisor_association')->checkbox()->label(false); ?>
  </div>
   <div class="col-sm-4 col-xs-4">
    <?php
    if($roleAssociation){
      if($roleAssociation->has_residential_association == 1){
        $modelRoleAssociation->has_residential_association = true;
      }
    }
    ?>
    <?=$form->field($modelRoleAssociation, 'has_residential_association')->checkbox()->label(false); ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
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
</div>
<?php ActiveForm::end();?>
