<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PublicGatheringMethods */

$this->title = Yii::t('app', 'Update Public Gathering Methods: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Public Gathering Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="public-gathering-methods-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
