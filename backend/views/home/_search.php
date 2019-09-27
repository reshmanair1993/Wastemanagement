<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsHomeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-home-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'sub_title') ?>

    <?= $form->field($model, 'fk_image_banner') ?>

    <?= $form->field($model, 'top_box_one_title') ?>

    <?php // echo $form->field($model, 'top_box_one_sub') ?>

    <?php // echo $form->field($model, 'fk_image_top_box_one') ?>

    <?php // echo $form->field($model, 'top_box_two_title') ?>

    <?php // echo $form->field($model, 'top_box_two_sub') ?>

    <?php // echo $form->field($model, 'fk_image_top_box_two') ?>

    <?php // echo $form->field($model, 'top_box_three_title') ?>

    <?php // echo $form->field($model, 'top_box_three_sub') ?>

    <?php // echo $form->field($model, 'fk_image_top_box_three') ?>

    <?php // echo $form->field($model, 'abt_head_one') ?>

    <?php // echo $form->field($model, 'abt_head_two') ?>

    <?php // echo $form->field($model, 'abt_head_three') ?>

    <?php // echo $form->field($model, 'abt_head_four') ?>

    <?php // echo $form->field($model, 'fk_image_abt') ?>

    <?php // echo $form->field($model, 'mid_four_title') ?>

    <?php // echo $form->field($model, 'mid_four_sub_title') ?>

    <?php // echo $form->field($model, 'mid_four_one_title') ?>

    <?php // echo $form->field($model, 'mid_four_one_sub_title') ?>

    <?php // echo $form->field($model, 'fk_image_mid_four_one') ?>

    <?php // echo $form->field($model, 'mid_four_two_title') ?>

    <?php // echo $form->field($model, 'mid_four_two_sub_title') ?>

    <?php // echo $form->field($model, 'fk_image_mid_four_two') ?>

    <?php // echo $form->field($model, 'mid_four_three_title') ?>

    <?php // echo $form->field($model, 'mid_four_three_sub_title') ?>

    <?php // echo $form->field($model, 'fk_image_mid_four_three') ?>

    <?php // echo $form->field($model, 'mid_four_four_title') ?>

    <?php // echo $form->field($model, 'mid_four_four_sub_title') ?>

    <?php // echo $form->field($model, 'fk_image_mid_four_four') ?>

    <?php // echo $form->field($model, 'video_title') ?>

    <?php // echo $form->field($model, 'video_sub_title') ?>

    <?php // echo $form->field($model, 'video_url') ?>

    <?php // echo $form->field($model, 'circle_menu_one') ?>

    <?php // echo $form->field($model, 'fk_image_circle_menu_one') ?>

    <?php // echo $form->field($model, 'circle_menu_two') ?>

    <?php // echo $form->field($model, 'fk_image_circle_menu_two') ?>

    <?php // echo $form->field($model, 'circle_menu_three') ?>

    <?php // echo $form->field($model, 'fk_image_circle_menu_three') ?>

    <?php // echo $form->field($model, 'circle_menu_four') ?>

    <?php // echo $form->field($model, 'fk_image_circle_menu_four') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
