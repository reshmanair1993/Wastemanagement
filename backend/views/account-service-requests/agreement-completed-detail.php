<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Service;
use backend\models\ResidentialAssociation;
use backend\models\AccountService;
$modelAccountService= new AccountService;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
  $modelUser  = Yii::$app->user->identity;
  $userRole = $modelUser->role;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Agreement Completed</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Agreement Completed', 'url' => ['account-service-requests/agreement-completed']];
   $this->title =  'View';

$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin();?>
        <?php 
        $key =0 ;
        foreach($serviceEstimate as $estimate):
          ?>
        <div class="row">
         <div class="col-sm-4 col-xs-4" data-id="<?= $key?>">
                <div class="form-group">
                    <div class="col-sm-7 col-xs-4">
                        <?php $service=Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['not',['is_package'=>1]])->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($model, "[$key]id")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$estimate['id'],'id'=>'service-id-'.$estimate['id']])->label('Service')?>
                    </div>
                </div>
            </div>
          <div class="col-sm-4 col-xs-4">
            <div class="form-group">
              <div class="col-sm-4 col-xs-4">
                <?php
                echo $form->field($model, "[$key]estimated_qty_kg")->textInput(['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>$estimate['estimated_qty_kg'],'id'=>'qty-kg-'.$estimate['id']])->label('Estimated Qty Kg');
                ?>
              </div>
            </div>
          </div>
           <div class="col-sm-4 col-xs-3">
            <div class="form-group">
              <div class="col-sm-6 col-xs-3">
                <?php
                 echo $form->field($model, "[$key]type")->dropDownList([1=>'Enable Request',0=>'Disable Request'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'request-type','value'=>$estimate['type']])
                ?>
              </div>
            </div>
          </div>
          </div>
      <?php 
      $key++;
      endforeach;?>
      <?php if($model->is_pre_approved!=1&&$model->pre_verification_needed!=1):?>
        <label id="reverificationBtn" class="label label-success">Reverification</label>
      <section class="reverification">
       <div class="col-sm-5 col-xs-5">
            <div class="form-group">
              <div class="col-sm-5 col-xs-5">
                <?php
                echo $form->field($modelAccountService, "pre_verification_remarks")->textArea(['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->pre_verification_remarks])->label('Reverification Remarks');
                ?>
              </div>
            </div>
          </div>
        <?php else:?>
          <div class="col-sm-5 col-xs-5">
            <div class="form-group">
              <div class="col-sm-5 col-xs-5">
          <H3>Re Verification Requested</H3>
          </div>
          </div>
          </div>
          </section>
        <?php endif;?>
        
        <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
<?php
$this->registerJs("
$('.datepicker').datepicker({
         orientation:'top',
         format:'dd-mm-yyyy',
         autoclose:true,
         todayHighlight:true,
     });
$('#reverificationBtn').on('click', function() {
      $('.reverification').show();
    });
    $(document).ready(function(){
      $('.reverification').hide();
      });
",View::POS_END);
?>
