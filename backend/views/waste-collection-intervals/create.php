<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WasteCollectionInterval */

$this->title = Yii::t('app', 'Create Waste Collection Interval');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Waste Collection Intervals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waste-collection-interval-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
