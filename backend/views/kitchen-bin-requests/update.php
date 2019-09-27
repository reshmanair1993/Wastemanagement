<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KitchenBinRequest */

$this->title = Yii::t('app', 'Update Kitchen Bin Request: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kitchen Bin Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kitchen-bin-request-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
