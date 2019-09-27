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
    <h4 class="page-title">HKS Evaluation Configuration</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
$breadcrumb[] = ['label' => 'HKS Evaluation Configuration'];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Customer Rating</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Waste Quality</a></li>
            <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Complaints Count</a></li>
            <li><a href="#tab_4" data-toggle="tab" aria-expanded="false">Time of Service Completion</a></li>
             <li><a href="#tab_5" data-toggle="tab" aria-expanded="false">Percentage of Service Completion</a></li>
              <li><a href="#tab_7" data-toggle="tab" aria-expanded="false">Percentage of Complaints Completion</a></li> 
             <li><a href="#tab_6" data-toggle="tab" aria-expanded="false">HKS evaluation configuration settings</a></li> 
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
               <?=$this->render('performance/customer-rating', [
                        'model'=> $model,
                        'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
                        'dataProvider'  =>$dataProvider,
                        ]);?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('performance/waste-quality', [
                        'model'=> $model,
                        'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
                        'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_3">
                    <?=$this->render('performance/complaints-count', [
                        'model'=> $model,
                        'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
                        'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_4">
                    <?=$this->render('performance/time-of-completion', [
                        'model'=> $model,
                        'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
                        'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
                        ]);?>
                </div>
                <div class="tab-pane" id="tab_5">
                    <?=$this->render('performance/percentage-of-completion', [
                        'model'=> $model,
                        'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
                        'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
                        ]);?>
                </div>
                <div class="tab-pane" id="tab_7">
                    <?=$this->render('performance/complaint-resolution', [
                        'model'=> $model,
                        'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
                        'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
                        ]);?>
                </div>
                <div class="tab-pane" id="tab_6">
                    <?=$this->render('performance/settings', [
                        'model'=> $model,
                        ]);?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
