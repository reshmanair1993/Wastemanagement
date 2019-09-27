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
    <h4 class="page-title">Change password</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">

   <?php
   $breadcrumb[] = ['label'=> 'Users', 'url'=> Url::to(['rbac/users-index','type'=>$type])];
    $breadcrumb[] = ['label' => 'Change password','template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,
'id' =>'pjax-admin-settings-add','options'=>['data-loader'=>'.preloader']]);?>
<?php
	$form = ActiveForm::begin(['action' => ['rbac/set-user-password','id'=>$id,'type'=>$type],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
?>
<br>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
         <div class="col-sm-12 col-xs-12">
          <div class="form-group">
            <div class="col-sm-12 col-xs-6">
              <?= $form->field($modelAccount, 'password')->passwordInput(['class'=>'form-control form-control-line','placeholder' => 'Enter new password'])?>
           </div>
         </div>
         </div>
         <div class="col-sm-12 col-xs-12">
          <div class="form-group">
            <div class="col-sm-12 col-xs-6">
              <?= $form->field($modelAccount, 'confirm_password')->passwordInput(['class'=>'form-control form-control-line','placeholder' => 'Re-enter new password'])?>
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