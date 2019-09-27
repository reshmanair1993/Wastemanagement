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
?>

<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">User</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
   <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Users', 'url' => ['users-index']];
   if($modelAccount->id){
     $this->title = $modelAccount->username;
   }else{
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
        if(!$modelAccount->id){
          $form = ActiveForm::begin([
            'action' =>['create-user'],
            'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
          }else{
            $form = ActiveForm::begin([
              'action' =>['update-user','user_id'=>$modelAccount->id],
              'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
          }
        ?>
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
          <div class="row">
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
           <div class="col-sm-6 col-xs-6">
             <div class="form-group">
               <div class="col-sm-6 col-xs-6">
                 <?php
                    $user_id = $modelAuthAssociation->user_id;
                    if($user_id){
                      $modelAccount = \backend\models\Account::find()->where(['status'=>1,'id'=>$user_id])->one();
                      $role = $modelAccount->role;
                    }
                    $roleName = isset($role)?$role:'admin-lsgi';
                   echo $form->field($modelAuthItem, 'name')->dropDownList($roleList, ['value'=>$roleName,'prompt' => 'Select from the list','id'=>'role-name','class'=>'form-control form-control-line'])->label('Role');
                   ?>
               </div>
             </div>
           </div>
         </div>
         <div class="row">
           <div class="col-sm-6 col-xs-6">
             <div class="form-group">
               <div class="col-sm-6 col-xs-6">
                 <div class="district">
                 <?php
                 $districtId = $modelAuthAssociation->district_id;
                 if($districtId){
                   $modelDistrict = \backend\models\District::find()->where(['status'=>1,'id'=>$districtId])->one();
                 }
                 $districtName = isset($modelDistrict)?$modelDistrict->name:'';
                 echo $form->field($modelAuthAssociation, 'district_id')->widget(DepDrop::classname(), [
                   'type'=>DepDrop::TYPE_SELECT2,
                   'data'=>[$districtName],
                   'options'=>['id'=>'role-district-id','class'=>'form-control form-control-line'],
                   'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                   'pluginOptions'=>[
                     'depends'=>['role-name'],
                     'class'=>'form-control form-control-line',
                     'placeholder'=>'Select...',
                     'url'=>Url::to(['rbac/get-district'])
                   ]
                   ])->label('District');
                 ?>
               </div>
                </div>
             </div>
          </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <div class="lsgi">
                  <?php
                  $lsgiId = $modelAuthAssociation->lsgi_id;
                  if($lsgiId){
                    $modelLsgi = \backend\models\Lsgi::find()->where(['status'=>1,'id'=>$lsgiId])->one();
                  }
                  $lsgiName = isset($modelLsgi)?$modelLsgi->name:'';
                  echo $form->field($modelAuthAssociation, 'lsgi_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'data'=>[$lsgiName],
                    'options'=>['id'=>'role-lsgi-id','class'=>'form-control form-control-line'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                      'depends'=>['role-name','role-district-id'],
                      'class'=>'form-control form-control-line',
                      'placeholder'=>'Select...',
                      'url'=>Url::to(['rbac/get-lsgi'])
                    ]
                    ])->label('Lsgi');
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <div class="ward">
                <?php
                $wardId = $modelAuthAssociation->ward_id;
                if($wardId){
                  $modelWard = \backend\models\Ward::find()->where(['status'=>1,'id'=>$wardId])->one();
                }
                $wardName = isset($modelWard)?$modelWard->name:'';
                echo $form->field($modelAuthAssociation, 'ward_id')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'data'=>[$wardName],
                  'options'=>['id'=>'role-ward-id','class'=>'form-control form-control-line'],
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                    'depends'=>['role-name','role-district-id','role-lsgi-id'],
                    'class'=>'form-control form-control-line',
                    'placeholder'=>'Select...',
                    'url'=>Url::to(['rbac/get-ward'])
                  ]
                  ])->label('Ward');
                ?>
              </div>
            </div>
          </div>
         </div>
         <div class="col-sm-6 col-xs-6">
           <div class="form-group">
             <div class="col-sm-6 col-xs-6">
               <div class="hks">
               <?php
               $hksId = $modelAuthAssociation->hks_id;
               if($hksId){
                 $modelGreenActionUnit = \backend\models\GreenActionUnit::find()->where(['status'=>1,'id'=>$hksId])->one();
               }
               $hksName = isset($modelGreenActionUnit)?$modelGreenActionUnit->name:'';
               echo $form->field($modelAuthAssociation, 'hks_id')->widget(DepDrop::classname(), [
                 'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$hksName],
                 'options'=>['id'=>'role-gau-id','class'=>'form-control form-control-line'],
                 'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                 'pluginOptions'=>[
                   'depends'=>['role-name','role-district-id','role-lsgi-id','role-ward-id'],
                   'class'=>'form-control form-control-line',
                   'placeholder'=>'Select...',
                   'url'=>Url::to(['rbac/get-green-action-unit'])
                 ]
                 ])->label('Hks');
               ?>
             </div>
             </div>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-6 col-xs-6">
           <div class="form-group">
             <div class="col-sm-6 col-xs-6">
               <div class="gt">
               <?php
               $gtId = $modelAuthAssociation->gt_id;
               if($gtId){
                 $modelAccount = \backend\models\Account::find()->where(['status'=>1,'id'=>$gtId])->one();
               }
               $gtName = isset($modelAccount)?$modelAccount->username:'';
               echo $form->field($modelAuthAssociation, 'gt_id')->widget(DepDrop::classname(), [
                 'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$gtName],
                 'options'=>['id'=>'role-gt-id','class'=>'form-control form-control-line'],
                 'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                 'pluginOptions'=>[
                   'depends'=>['role-name','role-district-id','role-lsgi-id','role-ward-id','role-gau-id'],
                   'class'=>'form-control form-control-line',
                   'placeholder'=>'Select...',
                   'url'=>Url::to(['rbac/get-gt']),

                 ]
                 ])->label('Gt');
               ?>
             </div>
             </div>
           </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
              <div class="survey-agency">
              <?php
              $surveyAgencyId = $modelAuthAssociation->survey_agency_id;
              if($surveyAgencyId){
                $modelSurveyAgency = \backend\models\SurveyAgency::find()->where(['status'=>1,'id'=>$surveyAgencyId])->one();
              }
              $surveyAgencyName = isset($modelSurveyAgency)?$modelSurveyAgency->name:'';
              echo $form->field($modelAuthAssociation, 'survey_agency_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                'data'=>[$surveyAgencyName],
                'options'=>['id'=>'role-survey-agency-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                  'depends'=>['role-name','role-district-id','role-lsgi-id'],
                  'class'=>'form-control form-control-line',
                  'placeholder'=>'Select...',
                  'url'=>Url::to(['rbac/get-survey-agency'])
                ]
                ])->label('Survey agency');
              ?>
            </div>
            </div>
          </div>
        </div>
      </div>
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

<?php
$url = Url::to(['get-role']);
$this->registerJs("

$('#role-name').on('change', function() {
  $('.district').hide();
  $('.lsgi').hide();
  $('.ward').hide();
  $('.hks').hide();
  $('.gt').hide();
  $('.survey-agency').hide();

  var name = $('#role-name').val();
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: 'get-role',
    data: {name:name},
    success:function(response) {
      var hasDistrict = response.district_association;
      var hasLsgi = response.has_lsgi_association;
      var hasWard = response.has_ward_association;
      var hasHks = response.has_hks_association;
      var hasGt = response.has_gt_association;
      var hasSurveyAgency = response.has_survey_agency_association;


      if(hasDistrict == 1){
        $('.district').show();
      }else{
        $('.district').hide();
      }

      if(hasLsgi == 1){
        $('.lsgi').show();
      }else{
        $('.lsgi').hide();
      }

      if(hasWard == 1){
        $('.ward').show();
      }else{
        $('.ward').hide();
      }

      if(hasHks == 1){
        $('.hks').show();
      }else{
        $('.hks').hide();
      }

      if(hasGt == 1){
        $('.gt').show();
      }else{
        $('.gt').hide();
      }

      if(hasSurveyAgency == 1){
        $('.survey-agency').show();
      }else{
        $('.survey-agency').hide();
      }

    }
  });
 });

 function hideRole(){
   var name = $('#role-name').val();
   if(name == ''){
     $('.district').hide();
     $('.lsgi').hide();
     $('.ward').hide();
     $('.hks').hide();
     $('.gt').hide();
     $('.survey-agency').hide();
   }
   $.ajax({
     type: 'POST',
     dataType: 'json',
     url: 'get-role',
     data: {name:name},
     success:function(response) {
       var hasDistrict = response.district_association;
       var hasLsgi = response.has_lsgi_association;
       var hasWard = response.has_ward_association;
       var hasHks = response.has_hks_association;
       var hasGt = response.has_gt_association;
       var hasSurveyAgency = response.has_survey_agency_association;


       if(hasDistrict == 1){
         $('.district').show();
       }else{
         $('.district').hide();
       }

       if(hasLsgi == 1){
         $('.lsgi').show();
       }else{
         $('.lsgi').hide();
       }

       if(hasWard == 1){
         $('.ward').show();
       }else{
         $('.ward').hide();
       }

       if(hasHks == 1){
         $('.hks').show();
       }else{
         $('.hks').hide();
       }

       if(hasGt == 1){
         $('.gt').show();
       }else{
         $('.gt').hide();
       }

       if(hasSurveyAgency == 1){
         $('.survey-agency').show();
       }else{
         $('.survey-agency').hide();
       }

     }
   });
 }
 hideRole();
");
?>