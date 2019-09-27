<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Service;
use backend\models\ResidentialAssociation;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
  $modelUser  = Yii::$app->user->identity;
  $userRole = $modelUser->role;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Complaints</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => ' Complaints', 'url' => ['requests/complaints']];
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
        <?php $form = ActiveForm::begin();?>
       <?php if($userRole!='customer'&&$userRole!='supervisor'):?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                $listData=ArrayHelper::map($modelPerson, 'id', 'first_name');
                echo $form->field($modelServiceRequest, 'supervisor')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>''])->label('Supervisor');
                ?>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                echo $form->field($modelServiceRequest, 'residential_association_id')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'options'=>[
                    'id'=>'service-residential_association-id',
                    'class'=>'form-control form-control-line',
                  ],
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                    'depends'=>['supervisor-person-id'],
                    'class'=>'form-control form-control-line',
                    'placeholder'=>'Select...',
                    'url'=>Url::to(['account-service-requests/get-residential-association'])
                  ]
                  ])->label('Residential Association');
                  ?>
              </div>
            </div>
          </div>
           <?php elseif($userRole!='customer'):?>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                echo $form->field($modelServiceRequest, 'supervisor')->hiddenInput(['class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>$modelUser->id])->label(false);
                ?>
              </div>
            </div>
          </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
               <?php
               $list=ResidentialAssociation::getAssociations($modelUser->id);
            $listData=ArrayHelper::map($list, 'id', 'name');

            echo $form->field($modelServiceRequest, 'residential_association_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-residential_association-id'])->label('Residential Association')
                  ?>
              </div>
            </div>
          </div> 
    <?php endif;?>
    <?php if($userRole!='customer'):?>
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                echo $form->field($modelServiceRequest, 'account_id_customer')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'options'=>[
                    'id'=>'service-customer-id',
                    'class'=>'form-control form-control-line',
                  ],
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                    'depends'=>['service-residential_association-id'],
                    'class'=>'form-control form-control-line',
                    'placeholder'=>'Select...',
                    'url'=>Url::to(['account-service-requests/get-service-customer-list'])
                  ]
                  ])->label('Customer Name');
                  ?>
              </div>
            </div>
          </div>
        <?php endif;?>
         </div>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                $list = $modelServiceRequest->getComplaintsList();
                 $listData=ArrayHelper::map($list, 'id', 'name');
                echo $form->field($modelServiceRequest, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-id','value'=>''])->label('Complaint');
                  ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?=$form->field($modelServiceRequest, 'new_complaint')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
              </div>
            </div>
          </div>
          
        </div>
         <?php if($userRole=='customer'):?>
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?=$form->field($modelServiceRequest, 'account_id_customer')->hiddenInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$modelUser->id])->label(false);?>
              </div>
            </div>
          </div>
      <?php endif;?>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
            <?=Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']);?>
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
",View::POS_END);
?>
