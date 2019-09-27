<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Lsgi */

$this->title = Yii::t('app', 'Create Lsgi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lsgi-create">

    <?= $this->render('_form', [
        'model' => $model,
		'types' => $types
    ]) ?>

</div>
