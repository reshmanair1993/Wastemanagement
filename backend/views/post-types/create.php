<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsPostTypes */

$this->title = Yii::t('app', 'Create Cms Post Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-post-types-create">

   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
