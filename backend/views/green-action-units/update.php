<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GreenActionUnit */

$this->title = Yii::t('app', 'Update Green Action Unit: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Green Action Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="green-action-unit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
