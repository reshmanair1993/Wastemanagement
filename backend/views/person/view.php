<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\person */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-view">

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
            'email:email',
            'first_name',
            'middle_name',
            'last_name',
            'phone1',
            'phone2',
            'dob',
            'address:ntext',
            'fk_state',
            'fk_district',
            'fk_locality',
            'gender_id',
            'created_at',
            'modified_at',
            'status',
            'fk_image_profile_pic',
            'fk_country_home',
            'fk_country_foreign',
            'fk_person_contact_home',
            'fk_person_contact_foreign',
            'fk_language_home',
            'fk_language_foreign',
        ],
    ]) ?>

</div>
