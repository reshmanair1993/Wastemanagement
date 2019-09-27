<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ParliamentConstituency */

$this->title = Yii::t('app', 'Create Parliament Constituency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parliament Constituencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parliament-constituency-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
