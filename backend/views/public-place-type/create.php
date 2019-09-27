<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PublicPlaceType */

$this->title = Yii::t('app', 'Create Public Place Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Public Place Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="public-place-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
