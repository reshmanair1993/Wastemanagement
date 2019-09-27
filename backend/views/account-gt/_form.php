<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountGt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-gt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id_customer')->textInput() ?>

    <?= $form->field($model, 'account_id_gt')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
