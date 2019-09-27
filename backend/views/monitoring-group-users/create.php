<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MonitoringGroupUser */

$this->title = Yii::t('app', 'Create Monitoring Group User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Group Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monitoring-group-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
