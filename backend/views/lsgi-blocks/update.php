<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Isgi */

$this->title = Yii::t('app', 'Update Lsgi Block: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Isgis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="isgi-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
