<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Ward */

$this->title = Yii::t('app', 'Create Ward');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ward-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
