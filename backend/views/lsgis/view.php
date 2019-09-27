<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Image;
$modelImage = new Image;

foreach($params as $param => $val)
  ${$param} = $val;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Lsgi</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <?php 
  $modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
 if(Yii::$app->user->can('lsgis-performance-evaluation')||$userRole=='super-admin'):?>
   <?= Html::a(Yii::t('app', 'HKS evaluation configuration'), ['lsgis/performance-evaluation?id='.$model->id],['data-pjax'=>0], ['class' => 'btn btn-success']) ?>
 <?php endif;?>
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Lsgi', 'url' => ['/lsgis']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->name);
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
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Lsgi Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Wards</a></li>
            <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Lsgi service Fee</a></li>
            <!-- <li><a href="#tab_4" data-toggle="tab" aria-expanded="false">Schedule</a></li> -->
            <li><a href="#tab_5" data-toggle="tab" aria-expanded="false">Escalation Settings</a></li>
            <li><a href="#tab_6" data-toggle="tab" aria-expanded="false">Lsgi service Slab Fee</a></li>
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-lsgi-edit','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
     <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'code')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'default_service_rate')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'default_complaint_rate')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'default_slab_rate')->textInput(['class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'camera_fault_calculation_interval_hours')->textInput(['class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $model->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->block_id)])->label('District')?>
                  </div>
             </div>
        </div>
    </div>
    <div class="row">
              <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
       <?php 

echo $form->field($model, 'assembly_constituency_id')->widget(DepDrop::classname(), [
    'type'=>DepDrop::TYPE_SELECT2,
    'data'=>[$model->getConstituency($model->block_id)],
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

echo $form->field($model, 'block_id')->widget(DepDrop::classname(), [
    'type'=>DepDrop::TYPE_SELECT2,
     'data'=>[$model->getBlock($model->block_id)],
    'options'=>['class'=>'form-control form-control-line'],
    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
    'pluginOptions'=>[
       'depends'=>['constituency-id'],
        'placeholder'=>'Select...',
        'class'=>'form-control form-control-line',
        'url'=>Url::to(['/lsgi-blocks/blocks'])
    ]
]);
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
                    $types= $model->getTypes();
                    $listData=ArrayHelper::map($types, 'id', 'name');
                    echo $form->field($model, 'lsgi_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-12 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
             </div>
             <div class="row">
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-6 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files1')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose header image'])?>
                 </div>
               </div>
             </div>
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-6 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files2')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose footer image'])?>
                 </div>
               </div>
             </div>
             </div>
             <div class="row">
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_camera_surveillance_required')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_wastemanagement_required')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            </div>
               <div class="row">
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?=$form->field($model, 'gst_no')->textInput(['class'=>'form-control form-control-line']);?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?=$form->field($model, 'cgst_percentage')->textInput(['class'=>'form-control form-control-line']);?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?=$form->field($model, 'sgst_percentage')->textInput(['class'=>'form-control form-control-line']);?>
                    </div>
                </div>
            </div>
            </div>
            
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>
        
                  <?php  ActiveForm::end();
                    Pjax::end();
                ?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('ward', [
                        'model'=> $model,
                        'modelWard'=> $modelWard,
                        'dataProvider'=> $dataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_3">
                    <?=$this->render('lsgi-service-fee', [
                        'model'=> $model,
                        'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
                        'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_4">
                    <?=$this->render('schedule', [
                        'model'=> $model,
                        'modelSchedule'  =>$modelSchedule,
                        'scheduleDataProvider'  =>$scheduleDataProvider,
                        ]);?>
                </div>
                <div class="tab-pane" id="tab_5">
                    <?=$this->render('escalation_settings', [
                        'model'=> $model,
                        'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_6">
                    <?=$this->render('lsgi-service-slab-fee', [
                        'model'=> $model,
                        'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
                        'lsgiServiceSlabFeeDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
                        ]);?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
