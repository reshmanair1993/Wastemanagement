<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;

foreach($params as $param => $val)
  ${$param} = $val;
?>

<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Set Qr Code</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">

   <?php
   $breadcrumb[] = ['label'=> 'Customers', 'url'=> Url::to(['customers/index'])];
    $breadcrumb[] = ['label' => 'Set Qr Code','template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,
'id' =>'pjax-admin-settings-add','options'=>['data-loader'=>'.preloader']]);?>
<?php
	$form = ActiveForm::begin(['action' => ['customers/set-qr-code?id='.$id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
?>
<br>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
         <div class="col-sm-12 col-xs-12">
          <div class="form-group">
            <div class="col-sm-12 col-xs-6">
              <?= $form->field($modelCustomer, 'qr_code_value')->textInput(['class'=>'form-control form-control-line','placeholder' => 'Enter qr code'])?>
           </div>
         </div>
         </div>
       </div>
     </div>
     <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
   </div>
 </div>
</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end();?>