<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GenerateChalan */

$this->title = 'Create Generate Chalan';
$this->params['breadcrumbs'][] = ['label' => 'Generate Chalans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generate-chalan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
