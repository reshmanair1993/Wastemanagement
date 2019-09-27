<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Schedule */

$this->title = Yii::t('app', 'Update Schedule: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schedule-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
