<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WasteType */

$this->title = Yii::t('app', 'Update Waste Collection Interval: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Waste Collection Interval'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="waste-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
