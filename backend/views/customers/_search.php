<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ward_id') ?>

    <?= $form->field($model, 'building_type_id') ?>

    <?= $form->field($model, 'door_status') ?>

    <?= $form->field($model, 'building_name') ?>

    <?php // echo $form->field($model, 'building_number') ?>

    <?php // echo $form->field($model, 'association_name') ?>

    <?php // echo $form->field($model, 'association_number') ?>

    <?php // echo $form->field($model, 'lead_person_name') ?>

    <?php // echo $form->field($model, 'lead_person_phone') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'building_owner_name') ?>

    <?php // echo $form->field($model, 'building_owner_phone') ?>

    <?php // echo $form->field($model, 'trading_type_id') ?>

    <?php // echo $form->field($model, 'shop_type_id') ?>

    <?php // echo $form->field($model, 'has_bio_waste') ?>

    <?php // echo $form->field($model, 'has_non_bio_waste') ?>

    <?php // echo $form->field($model, 'has_disposible_waste') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'fee_collection_interval_id') ?>

    <?php // echo $form->field($model, 'has_bio_waste_management_facility') ?>

    <?php // echo $form->field($model, 'bio_waste_management_facility_operational') ?>

    <?php // echo $form->field($model, 'bio_waste_management_facility_repair_help_needed') ?>

    <?php // echo $form->field($model, 'bio_waste_collection_method_id') ?>

    <?php // echo $form->field($model, 'bio_waste_collection_needed') ?>

    <?php // echo $form->field($model, 'non_bio_waste_collection_method_id') ?>

    <?php // echo $form->field($model, 'has_terrace_farming_interest') ?>

    <?php // echo $form->field($model, 'terrace_farming_help_type_id') ?>

    <?php // echo $form->field($model, 'creator_account_id') ?>

    <?php // echo $form->field($model, 'house_people_count') ?>

    <?php // echo $form->field($model, 'house_adult_count') ?>

    <?php // echo $form->field($model, 'house_children_count') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'modified_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
