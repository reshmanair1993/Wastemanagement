<?php
// use yii2assets\PrintThis;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use backend\models\Service;
use backend\models\LsgiServiceSlabFee;

// use yii\widget\PrintThis;
// namespace yii2assets\printthis;
?>
<link rel="stylesheet" href="../css/pdf.css">
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title"></h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
  
 </div>
</div>
<?php
  $form = ActiveForm::begin(['action' => ['incident-preview'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
?>
<div class="memo-preview">
<div class="container">
    <div class="logo-section">
      <div class=" row col-lg-12 col-md-12 col-sm-12 col-12 image-logo">
        <?php
        if(isset($modelLsgi->image_id)){
          $modelLogoImage        = $modelLsgi->fkImage;
          if(isset($modelLogoImage)?$modelLogoImage:''){
            $url = $modelLogoImage->uri_full;
            $path =  Yii::$app->params['logo_image_base_url'];
      ?>
            <img src="<?php echo $modelLogoImage->getFullUrl($url,$path);?>" />
      <?php
    }
  }
      ?>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
        <h1><b><?php
        if($modelLsgi)
         echo $modelLsgi->name ?>
       </b></h1>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
        <h5><b><?php
        if($modelLsgi)
         echo $modelLsgi->address ?>
       </b></h5>
      </div>   
    </div>
  <hr>
  <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
        <h3><b><?php
        
         echo 'TAX INVOICE' ?>
       </b></h3>
      </div>
   <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
        <h5><?php
        if($modelLsgi)
         echo 'GSTIN '.$modelLsgi->gst_no ?>
       </h5>
      </div>
      <br>
    <div class="row">
    <div class="col-md-8">
      <b>Bill To </b><br>
      
      <h4><b><?=$modelCustomer->lead_person_name?></b></h4>
        <h5><?=$modelCustomer->address?></h5>
      </div>
      <div class="col-md-4">
       <h5>Date : <?=date('d-M-Y',strtotime($modelPaymentRequest->created_at))?></h5>
      <h5>Invoice No : <?=$modelPaymentRequest->id?></h5>
        
      </div>
      </div>
     
      <div class="row">
       <table  class="newtbl">
      <tr><th>Sl.No</th><th>Description</th><th>Qty</th><th>Rate</th><th>Total</th></tr>
      <tr></tr>
        <?php 
        if($serviceEstimate){
          $i =1;
        foreach ($serviceEstimate as $key => $value) {
          if($value['id']!=null&&$value['estimated_qty_kg'])
          {
            $modelService = Service::find()->where(['id'=>$value['id']])->one();
            if($modelService){
              if($value['slab']==null&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])->andWhere(['collection_interval'=>$value['collection_interval']])
          ->andWhere(['is','slab_id',null])->andWhere(['status'=>1])->one();
        }
        else
        {
           $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()
              ->where(['lsgi_service_slab_fee.collection_interval'=>$value['collection_interval']])
              ->andWhere(['<','lsgi_service_slab_fee.start_value',$value['estimated_qty_kg']])
              ->andWhere(['>','lsgi_service_slab_fee.end_value',$value['estimated_qty_kg']])
              ->andWhere(['lsgi_service_slab_fee.service_id'=>$value['id']])
              ->andWhere(['lsgi_service_slab_fee.slab_id'=>$value['slab']])
              ->one();
        }
        $amount =0;
        $amountKg =0;
        if(!isset($modelLsgiServiceSlabFee))
        {
          $amount = 0;
          $amountKg = 0;
        }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $amount =  ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
              $amountKg = $amount/$value['estimated_qty_kg'];
            }
            else
            {
              $amount =  $modelLsgiServiceSlabFee->amount*1.5;
              $amountKg = $amount/$value['estimated_qty_kg'];
            }
        }
            ?>
             <tr>
            <td><?=$i?></td>
            <td><?=$modelService->name?></td>
            <td><?=$value['estimated_qty_kg']?></td>
            <td><?=$amountKg?></td>
            <td><?=$amount?></td>
            <?php
          }
        }
        ?>
        </tr>
        <?php
        $i++;
        }
        }?>
        <tr></tr>
        <tr>
        <td></td>
        <td></td> 
        <td></td>
        <td>Taxable Value</td>
        <td><?=$advanceAmount?></td>
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>CGST <?=$modelLsgi->cgst_percentage?>%</td>
        <td><?=($advanceAmount*$modelLsgi->cgst_percentage)/100?></td>
        </tr>
        <tr></tr>
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>SGST <?=$modelLsgi->sgst_percentage?>%</td>
         <td><?=($advanceAmount*$modelLsgi->sgst_percentage)/100?></td>
        </tr>
        <tr>
           <td></td>
        <td><b></b></td> 
        <td></td>
        <td><b>Grand Total</b></td>
        <td><b><?=$advanceAmount +(($advanceAmount*$modelLsgi->cgst_percentage)/100 )+ (($advanceAmount*$modelLsgi->sgst_percentage)/100)?></b></td>
        </tr>
      
    </table>
    </div><br>
    <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
    <p>This is a computer generated invoice. No signature required</p>
    </div>
    </div>
    
    </div>
<?php
ActiveForm::end();
?>

<?php

$this->registerJs("
$('#print').click(function () {
  window.print();
});
",View::POS_END);
?>
