<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\modelPushMessages\Account;
use backend\modelPushMessages\Customer;
use backend\modelPushMessages\Service;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Push Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"> Push Messages</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Push Messages';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'send','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelPushMessage, 'message')->textArea(['prompt' => 'Type Message...','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
          </div>
           <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelPushMessage, 'message_ml')->textArea(['prompt' => 'Type message in malayalam...','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
          </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelPushMessage, 'type')->dropDownList([1=>'Lsgi',2=>'Harithakarmasena',3=>'Ward'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line configuration_type','id'=>'configuration_type'])?>
                    </div>
                </div>
            </div>
            <section id="lsgi">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?php
                      $options = ['class'=>'form-control ','prompt' => 'lsgi..','id'=>'lsgi'];
                      $lsgi=$modelPushMessage->getLsgi();
                      $listData=ArrayHelper::map($lsgi, 'id', 'name');

                      echo $form->field($modelPushMessage, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
                    </div>
                </div>
            </div>
            </section>
            <section id="hks">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?php
                      $options = ['class'=>'form-control ','prompt' => 'Hks..','id'=>'hks'];
                      $hks=$modelPushMessage->getHks();
                      $listData=ArrayHelper::map($hks, 'id', 'name');

                      echo $form->field($modelPushMessage, 'hks_id')->dropDownList($listData, $options)->label(false)?>
                    </div>
                </div>
            </div>
            </section>
            <section id="ward">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $options = ['class'=>'form-control ','prompt' => 'Ward..','id'=>'ward'];
                      $ward=$modelPushMessage->getWards();
                      $listData=ArrayHelper::map($ward, 'id', 'name');

                      echo $form->field($modelPushMessage, 'ward_id')->dropDownList($listData, $options)->label(false)?>
                </div>
            </div>
        </div>
        </section>  
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::button('Save',['class' => 'btn btn-success confirm_button','data-status' => 2])?>
    </p>
        </div>
      <?php ActiveForm::end(); ?>
      
      </div>
</div>
     <?php
     $count = 0;
 $this->registerJs("
       $(document).ready(function(){
         var val = $('#configuration_type').val();
      if(val==1)
      {
                $('#lsgi').show();
                $('#hks').hide();
                $('#ward').hide();
      }
      if(val==2)
      {
                $('#lsgi').hide();
                $('#hks').show();
                $('#ward').hide();
      }
      if(val==3)
      {
                $('#lsgi').hide();
                $('#hks').hide();
                $('#ward').show();
      }
      if(!val){
                $('#lsgi').hide();
                $('#hks').hide();
                $('#ward').hide(); 
                }              
            });
    $('#configuration_type').on('change', function() {
      var val = $('#configuration_type').val();
      if(val==1)
      {
               $('#lsgi').show();
                $('#hks').hide();
                $('#ward').hide();
      }
      if(val==2)
      {
               $('#lsgi').hide();
                $('#hks').show();
                $('#ward').hide();
      }
      if(val==3)
      {
                $('#lsgi').hide();
                $('#hks').hide();
                $('#ward').show();
      }
    });
    $('.confirm_button').click(function() {
    $('.search-form').submit();
 });
 ",View::POS_END);?>