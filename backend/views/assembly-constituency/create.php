<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AssemblyContituency */

$this->title = Yii::t('app', 'Create Assembly Constituency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assembly Contituencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assembly-contituency-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
