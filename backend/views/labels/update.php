<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BuildingType */

$this->title = Yii::t('app', 'Update Building Type: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Building Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="building-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
