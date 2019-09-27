<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AdministrationType */

$this->title = Yii::t('app', 'Create Administration Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administration Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="administration-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
