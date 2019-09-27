<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ResidenceCategory */

$this->title = Yii::t('app', 'Create Residence Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Residence Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="residence-category-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
