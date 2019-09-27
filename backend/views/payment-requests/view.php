<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

foreach($params as $param => $val)
  ${$param} = $val;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Payment Request</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Payment Request', 'url' => ['/payment-requests/index']];
   if($model->id){
   $this->title =  'details';
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
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Payment Request Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Payments</a></li>
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-lsgi-edit','options'=>['data-loader'=>'.preloader']]);
                    ?>
     <div class="row">
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Basic Informations</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Customer </b></td><td><?=$model->fkAccount->fkCustomer->lead_person_name?></td></tr>
        <tr><td><b>Service</b></td><td><?=isset($model->fkServiceRequest->fkService->name)?$model->fkServiceRequest->fkService->name:''?></td></tr>
        <tr><td><b>Requested Date </b></td><td><?=date('Y-m-d', strtotime($model->requested_date))?></td></tr>
        <tr><td><b>Amount </b></td><td><?=$model->amount?></td></tr>
        <tr><td><b>Amount Paid </b></td><td><?=$model->getAmountPaid($model->id)?></td></tr>
        <tr><td><b>Amount Pending </b></td><td><?=$model->amount - $model->getAmountPaid($model->id)?></td></tr>
        </table>
        </div>
      </div> 
        </div>
                  <?php
                    Pjax::end();
                ?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('fee/fee-paid', [
                        'model'=> $model,
                        'modelPayments'=> $modelPayments,
                        'dataProvider'=> $dataProvider,
                        ]);?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
<style>
  td, th {
    padding: 10px;
}
</style>