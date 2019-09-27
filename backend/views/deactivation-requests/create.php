<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DeactivationRequest */

$this->title = Yii::t('app', 'Create Deactivation Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deactivation Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deactivation-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
