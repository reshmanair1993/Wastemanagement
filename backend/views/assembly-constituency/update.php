<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AssemblyContituency */

$this->title = Yii::t('app', 'Update Assembly Contituency: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assembly Contituencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="assembly-contituency-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
