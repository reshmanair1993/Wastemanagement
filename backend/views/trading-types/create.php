<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TradingType */

$this->title = Yii::t('app', 'Create Trading Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trading Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trading-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
