<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AccountFee */

$this->title = Yii::t('app', 'Create Account Fee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Fees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-fee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
