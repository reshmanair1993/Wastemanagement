<?php

    use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\WasteCategory;
use yii\web\View;
    foreach ($params as $param => $val)
    {
        ${
            $param} = $val;
    }
    $lat = $model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:'';
    $lng =$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:'';
    $latCustomer = $model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lat:'';
    $lngCustomer =$model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lng:'';
    $cid = $model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->id:null;
$link = "/wastemanagement/backend/web/customers/view-details?id=".$cid;
$status = '';
if($model->fkServiceAssignment)
{
  if($model->fkServiceAssignment->door_status==1)
    $status = 'Open';
  else
    $status = ' Closed';
}
$marked = $model->marked_rating_value?$model->marked_rating_value:0;
$total = $model->total_rating_value?$model->total_rating_value:0;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Service Requests</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
       $breadcrumb[]                  = ['label' => 'Service Requests', 'url' => ['/service-requests/index']];
       $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Green Action Unit'), 'url' => ['index']];
           $this->title = 'Service Request';
       $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs', ['links' => $breadcrumb]);?>
 </div>
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Service Request Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Assign Gt</a></li>
            <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Service Status</a></li>
        </ul>
        <div class="tab-content" style="margin-left: 18px;height: 1018px;">
            <div class="tab-pane active" id="tab_1">
         <div class = 'col-md-6' style="overflow:hidden !important">
        <div class="panel panel-default" >
        <div class="panel-heading"><b>Basic Information</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Customer</b></td><td><a target="_blank" data-pjax=0 href=<?=$link?>><?=$model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lead_person_name:null?></a></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->address:null?></td></tr>
        <tr><td><b>Phone</b></td><td><?=$model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lead_person_phone:null?></td></tr>
        <tr><td><b>Service</b></td><td><?=$model->fkService->name?></td></tr>
        <tr><td><b>Customer Remarks</b></td><td><?=$model->remarks?$model->remarks:''?></td></tr>
        <tr><td><b>Requested Date</b></td><td><?=$model->requested_datetime?></td></tr>
        <tr><td><b>Green Technician</b></td><td><?=isset($model->fkServiceAssignment->fkAccount->fkPerson)?$model->fkServiceAssignment->fkAccount->fkPerson->first_name:''?></td></tr>
        <tr><td><b>Remarks</b></td><td><?=$model->fkServiceAssignment?$model->fkServiceAssignment->remarks:''?></td></tr>
        <tr><td><b>Door Status</b></td><td><?=$status?></td></tr>
        <tr><td><b>Collected Quality</b></td><td><?=$model->getQualityAssigned()?></td></tr>
        <tr><td><b>Collected Quantity </b></td><td><?=$model->fkServiceAssignment?$model->fkServiceAssignment->quantity:''?></td></tr>
        <tr><td><b>Service Status</b></td><td><?=$model->getStatus()?></td></tr>
        <tr><td><b>Rating</b></td><td><?=$marked.'/'.$total?></td></tr>
        </table>
        </div>
        </div>
        <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Marked Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$lat?>,<?=$lng?>&hl=es;z=14&output=embed"></iframe>
            </div>
             <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Customer Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$latCustomer?>,<?=$lngCustomer?>&hl=es;z=14&output=embed"></iframe>
            </div>
            </div>
            <div class="tab-pane" id="tab_2">
                    <?=$this->render('assign-gt', [
    'model'                      => $model,
    'modelServiceAssignment' => $modelServiceAssignment
]);?>
                </div>
                 <div class="tab-pane" id="tab_3">
                    <?=$this->render('assign-status', [
    'model'                      => $model,
    'modelServiceAssignment' => $modelServiceAssignment
]);?>
                </div>
            </div>
            </div>
            </div>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['add-gt','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  ?>
    <?= $form->field($modelServiceAssignment, 'lat_update_from')->hiddenInput(['id'=>'latitude1','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:''])->label(false) ?>

    <?= $form->field($modelServiceAssignment, 'lng_updated_from')->hiddenInput(['id'=>'longitude1','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:''])->label(false) ?>
      <?php
   ActiveForm::end();
  ?>         
 <style>
  td, th {
    padding: 10px;
}
</style>
