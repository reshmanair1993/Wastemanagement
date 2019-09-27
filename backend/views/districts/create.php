<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\District */

$this->title = Yii::t('app', 'Create District');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Districts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
