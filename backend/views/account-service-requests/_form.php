<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountServiceRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-service-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'request_type')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'is_approved')->textInput() ?>

    <?= $form->field($model, 'requested_at')->textInput() ?>

    <?= $form->field($model, 'approval_status_changed_at')->textInput() ?>

    <?= $form->field($model, 'account_id_requested_by')->textInput() ?>

    <?= $form->field($model, 'account_id_approved_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
