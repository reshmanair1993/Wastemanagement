<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FeeCollectionInterval */

$this->title = Yii::t('app', 'Update Fee Collection Interval: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fee Collection Intervals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="fee-collection-interval-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
