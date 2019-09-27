<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ShopType */

$this->title = Yii::t('app', 'Create Shop Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
