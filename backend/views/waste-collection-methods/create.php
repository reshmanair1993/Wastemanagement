<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WasteType */

$this->title = Yii::t('app', 'Create Waste Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Waste Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waste-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
