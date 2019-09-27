<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ParliamentConstituency */

$this->title = Yii::t('app', 'Update Parliament Constituency: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parliament Constituencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="parliament-constituency-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
