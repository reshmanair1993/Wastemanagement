<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Service */

$this->title = Yii::t('app', 'Update Service: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="service-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelImage' => $modelImage,
    ]) ?>

</div>
