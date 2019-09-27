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
<h3>Parent roles</h3>

<br>
<?php $form = ActiveForm::begin(['action' =>['update-roles','name'=>$name]]);?>

<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-12 col-xs-12">
      <?php
      $authItem = \backend\models\AuthItem::find()->where(['type'=>1])->andWhere(['<>','name',$name])->all();
      $list = ArrayHelper::map($authItem,'name','name');
      $modelAuthItem = new \backend\models\AuthItem;
      if(isset($parentRoleNameArray)){
        $modelAuthItem->rule_name = $parentRoleNameArray;
      }
      echo $form->field($modelAuthItem, 'rule_name')->checkboxList($list, ['class' => '','id'=>'','style'=>''])->label(false);
      ?>
      </div>
    </div>
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
