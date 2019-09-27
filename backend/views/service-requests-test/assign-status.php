<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
$modelImage = new Image;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-gt','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['method'=>'post','action' => ['add-status','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => true,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
         <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php 
                  // $gt=Service::getGt();
                  $status=$model->getStatusOption($model->service_id);
                $listData=ArrayHelper::map($status, 'id', 'value'); 
                echo $form->field($modelServiceAssignment, 'servicing_status_option_id')->dropDownList($listData,['id'=>'status-id','prompt' => 'Choose option','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->servicing_status_option_id:''])->label('Status Option')?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'remarks')->textArea(['rows' => 10,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->remarks:'']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'lat_update_from')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:''])->label('Latitude');?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'lng_updated_from')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:''])->label('Longitude');?>
                </div>
            </div>
        </div>
        <?php if($model->fkServiceAssignment->lat_update_from&&$model->fkServiceAssignment->lng_updated_from):
        $lat = $model->fkServiceAssignment->lat_update_from;
        $lng = $model->fkServiceAssignment->lng_updated_from;?>
<div class = 'col-md-12' style="overflow:hidden !important">
        <h4>Marked Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$lat?>,<?=$lng?>&hl=es;z=14&output=embed"></iframe>
            </div>
          <?php endif;?>
        <section id='quality'>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $quality=$model->getQuality();
                $listData=ArrayHelper::map($quality, 'id', 'name'); 
                echo $form->field($modelServiceAssignment, 'quality')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->quality:''])->label('Quality')?>
                </div>
            </div>
        </div>
        </section>
        <section id='quantity'>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelServiceAssignment, 'quantity')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->quantity:''])->label('Quantity');?>
                </div>
            </div>
        </div>
        </section>
	<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"></div>
	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
	<?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
 <?php Pjax::end(); ?>
 <?php
 if(isset($success)&&$success==true) {
      echo $this->render('/common/success',['message'=>'Successfully updated your profile','title'=>'Awesome']);
      $isConfirm = isset($confirmationCallback)?$confirmationCallback:null;
$title = isset($title)?$title:'Alert';
$type = isset($type)?$type:'success';
$message = 'Successfully update status';
$title = Html::encode(trim($title));
$message = Html::encode(trim($message));
$title =  $title;
$message =  $message; //but need to escape apppstrope
if(!$isConfirm)
  $this->registerJs("
    swal({title:'$title',text: '$message', type:'$type'});
  ");
else
  $this->registerJs("
    swal({title:'$title',text: '$message', type:'$type'},$isConfirm);
  ");
    }
 $this->registerJs("
       $(document).ready(function(){
         // var val = $('#status-id').val();
         // $('#quality').hide();
         // $('#quantity').hide();
        var status = $('#status-id').val();
            if(status){
                $.ajax({ 
                    dataType : 'json',
                    url:'status-ajax',
                    data:{status:status},
                    method:'POST',
                    success: function(data) {
                       if(data.ask_waste_quality==1)
                       {
                        $('#quality').show();
                       }
                       else
                       {
                        $('#quality').hide();
                       }
                       if(data.ask_waste_quantity==1)
                       {
                        $('#quantity').show();
                       }
                       else
                       {
                        $('#quantity').hide();
                       }

                       
                    }
                });
            }
     
    });
    $('#status-id').on('change',function(){
            var status = $('#status-id').val();
            if(status){
                $.ajax({ 
                    dataType : 'json',
                    url:'status-ajax',
                    data:{status:status},
                    method:'POST',
                    success: function(data) {
                       if(data.ask_waste_quality==1)
                       {
                        $('#quality').show();
                       }
                       else
                       {
                        $('#quality').hide();
                       }
                       if(data.ask_waste_quantity==1)
                       {
                        $('#quantity').show();
                       }
                       else
                       {
                        $('#quantity').hide();
                       }

                       
                    }
                });
            }
        })
 ",View::POS_END);?>