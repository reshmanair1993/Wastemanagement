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
    <h4 class="page-title">Subscription</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Subscription', 'url' => ['account-service-requests/non-residential']];
   $this->title =  'Create';

$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => ['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);?>
        <?php 
        $key =0 ;
        foreach($serviceEstimate as $estimate):?>
         <div class="col-sm-5 col-xs-5" data-id="<?= $key?>">
                <div class="form-group">
                    <div class="col-sm-5 col-xs-5">
                        <?php $service=Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['not',['is_package'=>1]])->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($model, "[$key]id")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$estimate['id'],'id'=>'service-id-'.$estimate['id']])->label('Service')?>
                    </div>
                </div>
            </div>
          <div class="col-sm-5 col-xs-5">
            <div class="form-group">
              <div class="col-sm-5 col-xs-5">
                <?php
                echo $form->field($model, "[$key]estimated_qty_kg")->textInput(['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>$estimate['estimated_qty_kg'],'id'=>'qty-kg-'.$estimate['id']])->label('Estimated Qty Kg');
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-1">
          <?php $deleteUrl = Yii::$app->urlManager->createUrl(['account-service-requests/delete-request-service','id'=>$model->id,'service'=>$estimate['id'],'qty'=>$estimate['estimated_qty_kg']]);?>
  <a onclick="ConfirmDelete(function(){
    deleteItem('<?=$deleteUrl?>','#pjax-account-service-requests-non-residentiallist',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a>
            
          </div>
      <?php 
      $key++;
      endforeach;?>
      <?php if($model->is_pre_approved!=1&&$model->pre_verification_needed!=1):?>
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
          <H3>Verification Requested</H3>
          </div>
          </div>
          </div>
        <?php endif;?>
        <div class="col-sm-5 col-xs-5">
          <div class="form-group">
            <div class="col-sm-5 col-xs-5">
            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']);?>
            </div>
          </div>
        </div>
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
$('#request-type').on('change', function() {
      var val = $('#request-type').val();
      if(val==1)
      {
                $('.sub_service_data').show();
      }
      if(val==0)
      {
                $('.sub_service_data').hide();
      }
    });
    $(document).ready(function(){
      $('.sub_service_data').hide();
      });
",View::POS_END);
?>
