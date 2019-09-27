<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\Ward;
use frontend\models\KitchenBinRequest;
use yii\helpers\ArrayHelper;
use yii\web\View;
$model = new KitchenBinRequest();
?>
  <!-- <section class="page-banner contact-banner"> -->
    <!-- <img src="../img/contact/banner.jpg" alt="lgart Trivandrum Banner" class="bg-img"> -->
    <!-- <div class="container"> -->
      <!-- <div class="contact-holder"> -->
        <!-- <div class="row"> -->
          <!-- <div class="col-lg-12 col-md-12 col-lg-12 col-12"> -->
           <?php 
           // $form = ActiveForm::begin(['id' => 'kitchen-bin-requests','action'=>'site/kitchen-bin-requests']); 
           $form = ActiveForm::begin(['id' => 'kitchen-bin-requests','action'=>Yii::$app->UrlManager->createUrl(["site/kitchen-bin-requests"])]); 
           ?>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                  <?= $form->field($model, 'house_owner_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'house_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                  <div class="form-group">
                      <?=$form->field($model, 'residence_association')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                  </div>
              </div>
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'association_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?php $ward= Ward::find()->where(['status'=>1])->all();
                  $listData=ArrayHelper::map($ward, 'id', 'name_en');
                  echo $form->field($model, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'ward-id'])->label('Ward')?>
                </div>
              </div>                   
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?=$form->field($model, 'contact_no')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'adult_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'childrens_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'is_veg_farming')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Is Vegetable farming doing or not')?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                    <?= $form->field($model, 'address')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                </div>
              </div>
              <div class="col-lg-6 col-xs-6">
                <div class="form-group">
                  <?=$form->field($model, 'ownership_of_house')->dropDownList([1=>'Own ',2=>'Rented',3=>'Quarters',4=>'Others'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line ownership_house'])?>
                </div>
              </div>
            </div>

            <section id="owner_data">
              <div class="row">
                <div class="col-lg-6 col-xs-6">
                  <div class="form-group">
                      <?= $form->field($model, 'owner_name')->textArea(['maxlength' => true,'class'=>'form-control form-control-line ']) ?>
                  </div>
                </div>
                <div class="col-lg-6 col-xs-6">
                  <div class="form-group">
                      <?= $form->field($model, 'contact_number_owner')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                  </div>
                </div>
              </div>    
            </section>      
         
        <div class="col-lg-12 col-12">
          <div class="form-group">
              <div class="row">
                <div class="col-lg-6 col-6 ta-right">
                  <button class=" bt-primary bt-cancel" data-dismiss="modal">Cancel</button>
                </div>
                <div class="col-lg-6 col-6 ta-left">
                  <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn bt-primary']) ?>
                </div>
              </div>
          </div>
        </div>
            <?php ActiveForm::end(); ?>
    <?php
  //    if($success&&$success==1)  {
  //      $model = new KitchenBinRequest;
  //      $title = isset($title)?$title:'Success';
  //      $type = isset($type)?$type:'success';
  //      $message = isset($message)?$message:'Kitchen bin request has been added successfully';
  //      $title = Html::encode(trim($title));
  //      $message = Html::encode(trim($message));
  //      $title =  $title;
  //      $message =  $message;
  // $this->registerJs("
  // swal({title:'Success',text: '$message', type:'$type'});
  // // $.pjax.reload('#pjax-residential-ass-list');
  // ");
  // }

 $this->registerJs("
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
  <!-- </section> -->
