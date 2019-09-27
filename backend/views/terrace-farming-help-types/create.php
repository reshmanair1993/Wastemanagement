<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TerraceFarmingHelpType */

$this->title = Yii::t('app', 'Create Terrace Farming Help Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Terrace Farming Help Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="terrace-farming-help-type-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
