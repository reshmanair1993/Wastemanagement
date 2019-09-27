<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PaymentRequest */

$this->title = Yii::t('app', 'Create Payment Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
