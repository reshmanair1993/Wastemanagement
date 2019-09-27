<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
$modelLogin = $params['modelLogin'];
$form = ActiveForm::begin(['action' => ['account/reset-password'],'options' => ['data-pjax' => true]]);

?>
<div class="form-group">
  <?= $form->field($modelLogin, 'username')->textInput(array('placeholder' => 'Enter username here','id'=>'password-reset-email'))->label('Username') ?>
</div>
<?php
  // echo Html::submitButton('RESET',['class'=>'btn btn-orange']);
  ActiveForm::end();
  $this->registerJs("
    $('#fm-reset').show();

  ",View::POS_END); 

?>
