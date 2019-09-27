<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GreenActionUnit */

$this->title = Yii::t('app', 'Create Green Action Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Green Action Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="green-action-unit-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
