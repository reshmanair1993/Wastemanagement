<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\QrCode */
?>
<div class="qr-code-update">
    <?= $this->render('_form', [
        'modelQrCode' => $modelQrCode,
    ]) ?>

</div>
