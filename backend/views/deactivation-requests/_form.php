<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeactivationRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deactivation-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id_customer')->textInput() ?>

    <?= $form->field($model, 'account_id_gt')->textInput() ?>

    <?= $form->field($model, 'account_id_requested_by')->textInput() ?>

    <?= $form->field($model, 'account_id_status_updated_by')->textInput() ?>

    <?= $form->field($model, 'requested_datetime')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
