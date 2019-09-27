<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FeeCollectionInterval */

$this->title = Yii::t('app', 'Create Fee Collection Interval');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fee Collection Intervals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fee-collection-interval-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
