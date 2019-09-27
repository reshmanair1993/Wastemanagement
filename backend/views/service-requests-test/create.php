<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ServiceRequest */

$this->title = Yii::t('app', 'Create Service Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-request-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
