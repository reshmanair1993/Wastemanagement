<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\ResidentialAssociation;
use backend\models\Ward;
/* @var $this yii\web\View */
/* @var $model backend\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Kitchen Bin Request</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Kitchen Bin Request', 'url' => ['/kitchen-bin-requests/index']];
   $this->title = Yii::t('app', 'Create Kitchen Bin Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kitchen Bin Request'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->house_owner_name);
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
        <?php $form = ActiveForm::begin(); ?>
  <div class="row">
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'house_owner_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'house_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'residence_association')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?> 
                    <!--  <?php $association= ResidentialAssociation::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($association, 'id', 'name');
                    echo $form->field($model, 'residence_association')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'association-id'])->label('Residential Association')?> -->
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'association_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
        </div>
     <div class="row">
     <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
           <!--  <?php

            echo $form->field($model, 'ward_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getWard($model->ward_id)],
                 'options'=>['id'=>'ward-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/wards/get-wards'])
                ]
            ])->label('Ward');
            ?> -->
            <?php $ward= Ward::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($ward, 'id', 'name_en');
                    echo $form->field($model, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'ward-id'])->label('Ward')?>
                    </div>
                </div>
            </div>
           
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'contact_no')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        </div>
         </div>
          <div class="row">
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'adult_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'childrens_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_veg_farming')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Is Vegetable farming doing or not')?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
       <div class="col-sm-6 col-xs-6">
          <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'address')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
            </div>
        </div>
       
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'ownership_of_house')->dropDownList([1=>'Own ',2=>'Rented',3=>'Quarters',4=>'Others'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line ownership_house'])?>
                </div>
            </div>
        </div>
         </div>
    <section id="owner_data">
    
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6 ">
                    <?= $form->field($model, 'owner_name')->textArea(['maxlength' => true,'class'=>'form-control form-control-line ']) ?>
                </div>
            </div>
          </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'contact_number_owner')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
          </div>
        </div>
      </section>
      
         
        <div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
        </div>
        </div>

            <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
<?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });
       $(document).ready(function(){
         var val = $('.ownership_house').val();
      if(val==1||val==3||val==4)
      {
                $('#owner_data').hide();
      }
      if(val==2)
      {
               $('#owner_data').show();
      }
      if(!val){
                $('#owner_data').hide();
                }                
            });
    $('.ownership_house').on('change', function() {
      var val = $('.ownership_house').val();
     if(val==1||val==3||val==4)
      {
                $('#owner_data').hide();

      }
      if(val==2)
      {
               $('#owner_data').show();
      }    
    });
    
 ",View::POS_END);?>