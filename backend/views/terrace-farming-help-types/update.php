<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TerraceFarmingHelpType */

$this->title = Yii::t('app', 'Update Terrace Farming Help Type: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Terrace Farming Help Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="terrace-farming-help-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
