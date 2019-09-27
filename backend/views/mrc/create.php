<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BuildingType */

$this->title = Yii::t('app', 'Create Building Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Building Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
