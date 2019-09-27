<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CameraServiceRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="camera-service-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'camera_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>