<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PublicGatheringMethods */

$this->title = Yii::t('app', 'Create Public Gathering Methods');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Public Gathering Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="public-gathering-methods-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
