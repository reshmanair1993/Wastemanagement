<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\IsgiType */

$this->title = Yii::t('app', 'Create Lsgi Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Isgi Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="isgi-type-create">

  
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
