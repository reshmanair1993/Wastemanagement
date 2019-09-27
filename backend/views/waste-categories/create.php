<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WasteCategory */

$this->title = Yii::t('app', 'Create Waste Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Waste Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waste-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
