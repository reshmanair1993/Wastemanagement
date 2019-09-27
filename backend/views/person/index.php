<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'People');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Person'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'email:email',
            'first_name',
            'middle_name',
            'last_name',
            //'phone1',
            //'phone2',
            //'dob',
            //'address:ntext',
            //'fk_state',
            //'fk_district',
            //'fk_locality',
            //'gender_id',
            //'created_at',
            //'modified_at',
            //'status',
            //'fk_image_profile_pic',
            //'fk_country_home',
            //'fk_country_foreign',
            //'fk_person_contact_home',
            //'fk_person_contact_foreign',
            //'fk_language_home',
            //'fk_language_foreign',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
