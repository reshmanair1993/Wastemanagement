<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Account */

$this->title = Yii::t('app', 'Update Surveyor: {nameAttribute}', [
    'nameAttribute' => $modelAccount->username,
]);
// $modelPerson->id = $modelAccount->person_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelAccount->id, 'url' => ['view', 'id' => $modelAccount->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="account-update">
    <?= $this->render('_form', [
      'modelPerson' => $modelPerson,
      'modelAccount' => $modelAccount,
    ]) ?>

</div>
