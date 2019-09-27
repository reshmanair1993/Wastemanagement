<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ward_id')->textInput() ?>

    <?= $form->field($model, 'building_type_id')->textInput() ?>

    <?= $form->field($model, 'door_status')->textInput() ?>

    <?= $form->field($model, 'building_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'building_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'association_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'association_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lead_person_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lead_person_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'building_owner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'building_owner_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trading_type_id')->textInput() ?>

    <?= $form->field($model, 'shop_type_id')->textInput() ?>

    <?= $form->field($model, 'has_bio_waste')->textInput() ?>

    <?= $form->field($model, 'has_non_bio_waste')->textInput() ?>

    <?= $form->field($model, 'has_disposible_waste')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'lng')->textInput() ?>

    <?= $form->field($model, 'fee_collection_interval_id')->textInput() ?>

    <?= $form->field($model, 'has_bio_waste_management_facility')->textInput() ?>

    <?= $form->field($model, 'bio_waste_management_facility_operational')->textInput() ?>

    <?= $form->field($model, 'bio_waste_management_facility_repair_help_needed')->textInput() ?>

    <?= $form->field($model, 'bio_waste_collection_method_id')->textInput() ?>

    <?= $form->field($model, 'bio_waste_collection_needed')->textInput() ?>

    <?= $form->field($model, 'non_bio_waste_collection_method_id')->textInput() ?>

    <?= $form->field($model, 'has_terrace_farming_interest')->textInput() ?>

    <?= $form->field($model, 'terrace_farming_help_type_id')->textInput() ?>

    <?= $form->field($model, 'creator_account_id')->textInput() ?>

    <?= $form->field($model, 'people_count')->textInput() ?>

    <?= $form->field($model, 'house_adult_count')->textInput() ?>

    <?= $form->field($model, 'house_children_count')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
