<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsHome */

$this->title = Yii::t('app', 'Create Cms Home');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Homes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-home-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
