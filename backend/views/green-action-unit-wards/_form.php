<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
    <?php $form = ActiveForm::begin();?>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $unit= $model->getUnit();
                    $listData=ArrayHelper::map($unit, 'id', 'name');
                    echo $form->field($model, 'green_action_unit_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $ward= $model->getWard();
                    $listData=ArrayHelper::map($ward, 'id', 'name');
                    echo $form->field($model, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
            </div>
        </div>
      <div class="col-sm-3 col-xs-3">
            <div class="form-group">
                <div class="col-sm-3 col-xs-3">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
                </div>
            </div>
    </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
