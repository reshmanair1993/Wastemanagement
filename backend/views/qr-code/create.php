<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\QrCode */

$this->title = Yii::t('app', 'Create Qr Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Qr Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qr-code-create">
    <?= $this->render('_form', [
        'modelQrCode' => $modelQrCode,
    ]) ?>

</div>
