<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsEnquiries */

$this->title = Yii::t('app', 'Create Cms Enquiries');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Enquiries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-enquiries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
