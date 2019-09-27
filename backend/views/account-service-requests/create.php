<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AccountServiceRequest */

$this->title = Yii::t('app', 'Create Account Service Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Service Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-service-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
