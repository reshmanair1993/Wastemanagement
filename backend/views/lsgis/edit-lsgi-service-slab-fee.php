<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use backend\models\Slab;
use backend\models\NonResidentialWasteCollectionInterval;
use yii\web\View;
  Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-lsgi-service-slab-fee','options'=>['data-loader'=>'.preloader']]);
  $form = ActiveForm::begin(['action' => ['update-lsgi-service-slab-fee','id'=>$modelLsgiServiceSlabFee->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  ?>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-formn edit-qa" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
   <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                   <div class="col-sm-6 col-xs-6">
                <?php $service= Service::find()->where(['status'=> 1])->andWhere(['is_quantity_entering_enabled'=>1])->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($modelLsgiServiceSlabFee, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Service')?>
                </div>
         </div>
         </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $slab= Slab::find()->where(['status'=>1])->all();
                $listData=ArrayHelper::map($slab, 'id', 'name');
                echo $form->field($modelLsgiServiceSlabFee, 'slab_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Slab')?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $waste= NonResidentialWasteCollectionInterval::find()->where(['status'=>1])->all();
                $listData=ArrayHelper::map($waste, 'id', 'name');
                echo $form->field($modelLsgiServiceSlabFee, 'collection_interval')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Collection Interval')?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'start_value')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'end_value')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
  <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="col-sm-12 col-xs-6">
                     <?=$form->field($modelLsgiServiceSlabFee, 'amount')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                 </div>
               </div>
             </div>
             <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'corporation_percentage')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'service_provider_percentage')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelLsgiServiceSlabFee, 'use_for_per_kg_rate')->checkbox(); ?>
                    </div>
                </div>
            </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
  <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success pull-left']);
  ?>
  </div>
  <?php
   ActiveForm::end();
  ?>
  </div>
  <?php
  $this->registerJs("

function toggleCloseAddDeal() {
   hideAllCollapses();
   $('.edit-qa').fadeIn('fast');
}

$('.close-job-toggle').click(function() {
     toggleCloseAddDeal();
   });
   function hideAllCollapses() {
  $('.collapse.in').collapse('hide'); //hide all open collapses
}
   ", View::POS_END);?>
   <?php
  $this->registerJs("
    $('#Pjax-edit-lsgi-service-slab-fee').on('pjax:end', function() {
      $.pjax.reload({container:'#Pjax-add-lsgi-service-slab-fee'});
    });

    ",View::POS_END);
?>
 <?php Pjax::end(); ?>