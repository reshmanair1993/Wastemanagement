<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsPost */

$this->title = Yii::t('app', 'Create Cms Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-post-create">

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>

</div>
