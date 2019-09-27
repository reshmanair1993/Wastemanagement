<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdministrationType */

$this->title = Yii::t('app', 'Update Administration Type: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administration Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="administration-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
