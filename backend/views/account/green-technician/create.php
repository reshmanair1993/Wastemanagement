<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Account */

$this->title = Yii::t('app', 'Create Surveyor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">
    <?= $this->render('_form', [
        'modelAccount' => $modelAccount,
        'modelPerson' => $modelPerson,
    ]) ?>

</div>
