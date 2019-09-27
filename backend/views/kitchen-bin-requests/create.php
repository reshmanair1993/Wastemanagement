<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KitchenBinRequest */

$this->title = Yii::t('app', 'Create Kitchen Bin Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kitchen Bin Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kitchen-bin-request-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
