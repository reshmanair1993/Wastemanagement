<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Service;
use backend\models\BuildingType;
use backend\models\NonResidentialWasteCollectionInterval;
use backend\models\QrCode;
use backend\models\Slab;
$modelQrcode = new QrCode;
use backend\models\AccountService;
$modelAccountService= new AccountService;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
  $modelUser  = Yii::$app->user->identity;
  $userRole = $modelUser->role;
  $count = 0;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Non Residential Customers</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Non Residential Customers', 'url' => ['account-service-requests/customers-list']];
   $this->title =  'Waste Collection Registration Form';

$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => ['update-service-enabling-request','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);?>
        <div class="row">
         <!--  <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                        <?= $form->field($modelCustomer, 'qr_code')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$modelQrcode->value])->label('QR Code') ?>
                    </div>
                </div>
            </div> -->
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?= $form->field($modelCustomer, 'address')->textarea(['rows' => 6,'class'=>'form-control form-control-line']) ?>
              </div>
            </div>
          </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?= $form->field($modelCustomer, 'lead_person_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Owner') ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?= $form->field($modelCustomer, 'lead_person_phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Owner Phone') ?>
              </div>
            </div>
          </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?= $form->field($modelCustomer, 'building_owner_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])?>
              </div>
            </div>
          </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?= $form->field($modelCustomer, 'building_owner_phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])?>
              </div>
            </div>
          </div>
           <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $type= BuildingType::getTypeNonResidential();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($modelCustomer, 'building_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
              </div>
            </div>
          </div>
          </div>
                  <?php 
        $key =0 ;
        foreach($serviceEstimate as $estimate):
          ?>
        <div class="row">
         <div class="col-sm-3 col-xs-3" data-id="<?= $key?>">
                <div class="form-group">
                    <div class="col-sm-7 col-xs-4">
                        <?php $service=Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['is_non_residential'=>1])->andWhere(['is_quantity_entering_enabled'=>1])->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($model, "[$key]id")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$estimate['id'],'id'=>'service-id-'.$estimate['id']])->label('Waste Type')?>
                    </div>
                </div>
            </div>
          <div class="col-sm-2 col-xs-2">
            <div class="form-group">
              <div class="col-sm-2 col-xs-2">
                <?php
                echo $form->field($model, "[$key]estimated_qty_kg")->textInput(['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>$estimate['estimated_qty_kg'],'id'=>'qty-kg-'.$estimate['id']])->label('Qty');
                ?>
              </div>
            </div>
          </div>
           <div class="col-sm-3 col-xs-3" data-id="<?= $key?>">
                <div class="form-group">
                    <div class="col-sm-3 col-xs-3">
                        <?php $interval= NonResidentialWasteCollectionInterval::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($interval, 'id', 'name');
                    echo $form->field($model, "[$key]collection_interval")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$estimate['collection_interval'],'id'=>'collection_interval-'.$estimate['collection_interval']])->label('Interval')?>
                    </div>
                </div>
            </div>
                <?php if($key==0){?>
           <div class="col-sm-3 col-xs-3">
            <div class="form-group">
              <div class="col-sm-3 col-xs-3">
                <?php  $slab= Slab::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($slab, 'id', 'name');
                    echo $form->field($model, "[$key]slab")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line ','id'=>'slab-id-'.$key,'value'=>$estimate['slab']])->label('Slab')?>
              </div>
            </div>
          </div>
          <?php }else{?>
          
                <?= $form->field($model, "[$key]slab")->hiddenInput(['prompt' => 'Select from the list','class'=>'form-control form-control-line ','id'=>'slab-'.$key,'value'=>''])->label('')?>
             
          <?php
            }?>
           <div class="col-sm-3 col-xs-3">
            <div class="form-group">
              <div class="col-sm-6 col-xs-3">
                <?php
                 echo $form->field($model, "[$key]type")->dropDownList([1=>'Enable Request',0=>'Disable Request'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'request-type','value'=>$estimate['type']])
                ?>
              </div>
            </div>
          </div>
          <?php if($estimate['type']!=null&&$estimate['type']==0&&$userRole=='junior-health-inspector'){
            $count = $count+1;
            }?>
          <div class="col-md-1">
          <?php $deleteUrl = Yii::$app->urlManager->createUrl(['account-service-requests/delete-request-service','id'=>$model->id,'service'=>$estimate['id'],'qty'=>$estimate['estimated_qty_kg']]);?>
  <a onclick="ConfirmDelete(function(){
    deleteItem('<?=$deleteUrl?>','#pjax-account-service-requests-non-residentiallist',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a>
            
          </div>
          </div>
      <?php 
      $key++;
      endforeach;?>
      <?php if($count>0&&$userRole=='junior-health-inspector'){?>
        <div class="col-sm-5 col-xs-5">
            <div class="form-group">
              <div class="col-sm-5 col-xs-5">
                <?php
                echo $form->field($modelAccountService, "reason_for_disable")->textArea(['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->reason_for_disable]);
                ?>
              </div>
            </div>
          </div>
        <?php }?>
      <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']);?>
            </div>
          </div>
        </div>
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
        <?php elseif($model->pre_verification_needed==1):?>
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
    <br>
        <div class="row">
        <H3>Advance Amount : <?=$advanceAmount?></H3>
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
<?php
$this->registerJs("

 $('.collection-interval').on('change',function(){
  var id = $(this).attr('data-id');
  var interval = $('#collection-interval-id-'+id).val();
  var qty = $('#qty-kg-'+id).val();
  var service = $('#service-id-'+id).val();
            if(interval&&qty&&service){
                $.ajax({ 
                    url:'slab-ajax',
                    data:{interval:interval,qty:qty,service:service},
                    method:'POST',
                    success: function(data) {
                        var target = $('#slab-id-'+id);
                        target.empty();
                        $.each(data , function(key , value){
                          console.log(key);
                          console.log(value);
                            var input = '<option value='+key+' >'+value+'</option>';
                            target.append(input);
                        })
                    }
                });
            }
        })
", View::POS_END);
 ?>