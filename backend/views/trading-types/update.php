<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TradingType */

$this->title = Yii::t('app', 'Update Trading Type: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trading Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="trading-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
