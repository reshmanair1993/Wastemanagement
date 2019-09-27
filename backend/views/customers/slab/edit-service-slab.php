<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-service-slab','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['update-service-slab','id'=>$modelAccountSlabService->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-formn edit-qa" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 <div class="col-sm-12 col-xs-12">
			          <div class="form-group">
			             <div class="col-sm-6 col-xs-6">
                <?php $service= Service::find()
                ->leftjoin('account_service','account_service.service_id=service.id')
                ->where(['account_service.status'=> 1])
                ->andWhere(['service.status'=> 1])
                ->andWhere(['account_service.account_id'=> $modelAccount->id])
                ->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($modelAccountSlabService, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-id'])->label('Service')?>
                </div>
         </div>
         </div>
          <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="col-sm-12 col-xs-6">
               <?php 

                        echo $form->field($modelAccountSlabService, 'slab_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccountSlabService->getSlabName($modelAccountSlabService->slab_id)],
                        'options'=>['id'=>'slab-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['service-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/lsgis/get-slab'])
                        ]
                    ])->label('Slab');
                    ?>
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