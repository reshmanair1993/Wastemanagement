<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
?>

<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Survey Coordinator</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Survey Coordinator', 'url' => ['account/coordinators']];
if($modelAccount->id){
   $this->title =  $modelAccount->username;
}
else
{
   $this->title =  'Create';
}
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

        <?php 
    if($modelAccount->id){
    $form = ActiveForm::begin([
    'action' =>['account/update-coordinator','id'=>$modelAccount->id],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
    }
    else
      {
      $form = ActiveForm::begin([
    'action' =>['account/create-coordinator'],
    'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
        }?>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'username')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
            <?php if(!$modelAccount->id){?>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'password_hash')->passwordInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Password');?>
                </div>
              </div>
            </div>
            <?php }?>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Email');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'first_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('First Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'middle_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Middle Name');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'last_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Last Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone1')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 1');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone2')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 2');?>
                </div>
              </div>
            </div>
          </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                    $genders= $modelPerson->getGender();
                    $listData=ArrayHelper::map($genders, 'id', 'name');
                    echo $form->field($modelPerson, 'fk_gender')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Gender')?>
                </div>
              </div>
           </div>
           <?php if($userRole=='super-admin'):?>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $modelAccount->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($modelAccount, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$modelAccount->getDistrict($modelAccount->lsgi_id)])->label('District')?>
                  </div>
                </div>
              </div>
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelAccount, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getConstituency($modelAccount->lsgi_id)],
                        'options'=>['id'=>'constituency-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['district-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/assembly-constituency/constituency'])
                        ]
                    ])->label('Assembly Constituency');
                    ?>
                    </div>
                </div>
            </div>
          <div class="col-sm-6 col-xs-6">
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelAccount, 'block_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getBlock($modelAccount->lsgi_id)],
                        'options'=>['id'=>'block-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['constituency-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/lsgi-blocks/blocks'])
                        ]
                    ])->label('Block');
                    ?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getLsgis($modelAccount->lsgi_id)],
                 'options'=>['id'=>'lsgi-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['block-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/lsgis/lsgi'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
             <!-- <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'green_action_unit_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getUnit($modelAccount->green_action_unit_id)],
                'options'=>['class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/green-action-units/unit'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="row">
         <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'survey_agency_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getAgency($modelAccount->survey_agency_id)],
                'options'=>['class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/survey-agencies/agency'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
            </div>
             <?php endif;?>
      <?php if($userRole=='admin-lsgi'):?><?php

            echo $form->field($modelAccount, 'lsgi_id')->hiddenInput(['value'=>$modelUser->lsgi_id])->label(false);
            ?>
          <!--  <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php
        $unit= $modelAccount->getUnits($modelUser->lsgi_id);
                    $listData=ArrayHelper::map($unit, 'id', 'name');
                    echo $form->field($modelAccount, 'green_action_unit_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'unit-id'])->label('Haritha Karma Sena')?>
                  </div>
                </div>
              </div> -->
               <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $unit= $modelAccount->getSurveyAgency($modelUser->lsgi_id);
                    $listData=ArrayHelper::map($unit, 'id', 'name');
                    echo $form->field($modelAccount, 'survey_agency_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'unit-id'])->label('Survey Agency')?>
                  </div>
                </div>
              </div>
          <?php endif;?>
            <div class="row">
          <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
              <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
          </div>
          </div>
          </div>
        </div>
          <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
