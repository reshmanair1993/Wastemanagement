<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsHome */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Homes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-home-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'sub_title:ntext',
            'fk_image_banner',
            'top_box_one_title:ntext',
            'top_box_one_sub:ntext',
            'fk_image_top_box_one',
            'top_box_two_title:ntext',
            'top_box_two_sub:ntext',
            'fk_image_top_box_two',
            'top_box_three_title:ntext',
            'top_box_three_sub:ntext',
            'fk_image_top_box_three',
            'abt_head_one:ntext',
            'abt_head_two:ntext',
            'abt_head_three:ntext',
            'abt_head_four:ntext',
            'fk_image_abt',
            'mid_four_title:ntext',
            'mid_four_sub_title:ntext',
            'mid_four_one_title:ntext',
            'mid_four_one_sub_title:ntext',
            'fk_image_mid_four_one',
            'mid_four_two_title:ntext',
            'mid_four_two_sub_title:ntext',
            'fk_image_mid_four_two',
            'mid_four_three_title:ntext',
            'mid_four_three_sub_title:ntext',
            'fk_image_mid_four_three',
            'mid_four_four_title:ntext',
            'mid_four_four_sub_title:ntext',
            'fk_image_mid_four_four',
            'video_title:ntext',
            'video_sub_title:ntext',
            'video_url:ntext',
            'circle_menu_one:ntext',
            'fk_image_circle_menu_one',
            'circle_menu_two:ntext',
            'fk_image_circle_menu_two',
            'circle_menu_three:ntext',
            'fk_image_circle_menu_three',
            'circle_menu_four:ntext',
            'fk_image_circle_menu_four',
        ],
    ]) ?>

</div>
