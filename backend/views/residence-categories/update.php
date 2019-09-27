<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ResidenceCategory */

$this->title = Yii::t('app', 'Update Residence Category: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Residence Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="residence-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
