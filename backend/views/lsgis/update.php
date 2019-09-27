<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Lsgi */

$this->title = Yii::t('app', 'Update Lsgi: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="lsgi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
