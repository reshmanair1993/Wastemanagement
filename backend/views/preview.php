<?php
// use yii2assets\PrintThis;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

// use yii\widget\PrintThis;
// namespace yii2assets\printthis;

  $form = ActiveForm::begin(['action' => ['preview','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  $modelLsgi = $model->getLsgi($model->lsgi_id);
  $modelAuthorizedSignatory = $model->getAuthorizedSignatory($model->lsgi_authorized_signatory_id);
  $modelIncident = $model->getIncident($model->incident_id);
  if(isset($modelIncident)?$modelIncident:''){
    $modelCamera = $model->getCamera($modelIncident->camera_id);
    $modelIncidentName = $model->getIncidentType($modelIncident->id);
    $incidentImage = $modelIncident->image_id;
    $incidentVideo = $modelIncident->file_video_id;
    if($incidentVideo)
      $modelVideo        = $model->getVideo($incidentVideo);
    if($incidentImage)
      $modelImage        = $model->getImage($incidentImage);
  }
  $modelMemoType = $model->getMemoType($model->memo_type_id);
  $modelIncidentMetas = $modelIncident->getIncidentMeta($modelIncident->id);
  // print_r($modelIncidentMetas);exit;
?>
<?php
// echo PrintThis::widget([
// 	'htmlOptions' => [
// 		'id' => 'PrintThis',
// 		'btnClass' => 'btn btn-info',
// 		'btnId' => 'btnPrintThis',
// 		'btnText' => 'พิมพ์หน้านี้',
// 		'btnIcon' => 'fa fa-print'
// 	],
// 	'options' => [
// 		'debug' => false,
// 		'importCSS' => true,
// 		'importStyle' => false,
// 		'loadCSS' => "path/to/my.css",
// 		'pageTitle' => "",
// 		'removeInline' => false,
// 		'printDelay' => 333,
// 		'header' => null,
// 		'formValues' => true,
// 	]
// ]);
?>
<!-- <div id="PrintThis"> -->
<div class="container">
  <div class="memo-preview">
      <div class="logo-section">
        <div class=" row col-lg-12 col-md-12 col-sm-12 col-12 image-logo">
          <?php
          if($modelLsgi){
            $modelLogoImage        = $model->getImage($modelLsgi->image_id);
            // print_r($modelLogoImage);exit;
            if(isset($modelLogoImage)?$modelLogoImage:''){
              $url = $modelLogoImage->uri_full;
              print_r($url);exit;
              $path =  Yii::$app->params['logo_image_base_url'];
        ?>
              <img src="<?php echo $modelLogoImage->getFullUrl($url,$path);?>" />
        <?php
            }}
        ?>
          <!-- <img class="img-thumbnail" src="http://localhost/wastemanagement/common/uploads/logo/Seal_of_Corporation_of_Thiruvananthapuram.svg.png<?php //echo $modelImage->getFullUrl($url);?>" /> -->
        </div>
        <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
          <h1><b><?php
          if($modelLsgi)
           echo $modelLsgi->address ?></h1></b>
        </div>
        <div class="row col-lg-12 col-md-12 col-sm-12 col-12  field-padding subject">
          <label for="charge">Subject : </label>
          <?php
          echo ucfirst($model->subject); ?>
        </div>
      </div>
    <div class="memo-content-section">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding field-head">
          <label for="charge">Charge : </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding field-content">
          <?php
            if($modelMemoType)
              echo ucfirst($modelMemoType->title);
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-12  field-padding field-head">
          <label for="charge">Reference Rule : </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-12  field-padding field-content">
          <?php
          if($modelMemoType)
            echo ucfirst($modelMemoType->rule_url);
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-12  field-padding field-head">
          <label for="charge">Location : </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-12 field-content field-padding">
          <?php
          if(isset($modelCamera)?$modelCamera:''){
          $locationCap = "<a href='https://maps.google.com?q=".$modelCamera->lat.",".$modelCamera->lng."' target='_blank'>Open in map</a>";
          echo $locationCap;
        }
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-12  field-padding incident-description">
          <label for="charge">Incident Description : </label>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-6 col-12 description-box field-padding">
          <p>
          <?php
              echo ucfirst($model->description);
          ?>
        </p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12  field-padding">
          <label for="charge">Incident Meta Details: </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-6 col-12  field-padding">
            <p>
              <?php
              if($modelIncidentMetas){
              foreach ($modelIncidentMetas as $modelIncidentMeta) {
            ?>
              <?=$modelIncidentMeta->incident_key.':'?>
              <?=$modelIncidentMeta->value?>
            <?php
             }
           }
           ?>
          </p>
        </div>
      </div>
      <div class="row">
        <!-- <div class="row"> -->
          <div class="col-lg-12 col-md-12 col-sm-12 col-12  field-padding">
            <label for="charge">Proof : </label>
          </div>
          <div class="proof-section">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 memo-video-section">
              <?php
                  if(isset($modelVideo)?$modelVideo:''){
                    $url = $modelVideo->url;
              ?>
              <video controls>
              <source src="<?php echo $modelVideo->getFullUrl($url);?>" type="video/mp4">
              </video>
              <?php
                  }
              ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 memo-image-section">
              <?php
                  if(isset($modelImage)?$modelImage:''){
                    $url = $modelImage->uri_full;
              ?>
                    <img src="<?php echo $modelImage->getFullUrl($url);?>" />
              <?php
                  }
              ?>
            </div>
        </div>
        <!-- </div> -->
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 field-padding field-head">
          <label for="charge">Penal Actions :</label>
        </div>
      </div>
      <div class="row penalty">
        <h2><div class="col-lg-3 col-md-3 col-sm-6 col-12 field-padding field-head">
          <label for="charge">Penality Amount : </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-12 field-padding field-content">
          <b><?php
            echo '₹'.$model->amount.'/-';
          ?></b>
        </div>
      </h2>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-12 field-padding incident-description">
          <label for="charge">Other Legal Actions</label>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-6 col-12 description-box field-padding">
          <p>
          <?php
          if($modelMemoType)
            echo ucfirst($modelMemoType->other_legal_actions);
          ?>
        </p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 field-padding authorized-signatory">
          <label for="charge">Authorized Signatory</label>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 field-padding authorized-signatory">
          <?php
            if($modelAuthorizedSignatory){
              $modelSignatureImage        = $model->getImage($modelAuthorizedSignatory->image_id_signature);
              if(isset($modelSignatureImage)?$modelSignatureImage:''){
                $url = $modelSignatureImage->uri_full;
                $path =  Yii::$app->params['signature_image_base_url'];
          ?>
          <img src="<?php echo $modelSignatureImage->getFullUrl($url,$path);?>" />
          <?php
            }}
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 field-padding authorized-signatory-place">
          <b>Place : </b>
            <?php
            if($modelLsgi)
              echo $modelLsgi->name;
          ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 field-padding authorized-signatory authorized-signatory-name">

        <b><?php
          if($modelAuthorizedSignatory)
            echo ucfirst($modelAuthorizedSignatory->name);
          ?></b>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 field-padding authorized-signatory-date">
          <b>Date : </b>
          <?php
          $createdAt = Yii::$app->formatter->asTime($model->created_at, 'dd-MM-yyyy');
          echo $createdAt;
          ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 field-padding authorized-signatory authorized-signatory-position">
          <b><?php
          if($modelAuthorizedSignatory)
            echo ucfirst($modelAuthorizedSignatory->position);
          ?></b>
        </div>
      </div>
    </div>
   </div>
  </div>
<!-- </div> -->
<div class="col-lg-12 col-md-12 col-sm-6 col-12 field-padding">
  <?= Html::submitButton('Print', ['class' => 'print-btn btn btn-success','id' => 'print'],['target' => '_blank']) ?>
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
<style>
@media print {
  body h1{font-size: 16px !important;}
  .field-padding{
    margin-bottom: 0 !important;
  }
  .subject{
    font-size: 16px !important;
  }
  .image-logo img{
    height: 60px !important;
    margin-bottom: 0px !important;
  }
  .cooperation-address{
    margin-bottom: 0px !important;
  }
  .memo-preview{
    padding : 10px !important;
  }
  .memo-video-section{
    margin-left: 20px !important;
    width: 40% !important;
    display: inline-block !important;
    float: left !important;
    height: 100px !important;
  }
  .memo-image-section{
    margin-left: 20px !important;
    width: 40% !important;
    height: 100px !important;
    float: left !important;
  }
  .field-padding{
    margin-top: 5px !important;
    /* text-align: left !important; */
  }
  .authorized-signatory label{
    margin-top : 10px !important;
  }
  .field-content.col-md-9{
    width: 60.333333% !important;
    display: inline-block !important;
    float: left !important;
  }
  .field-head.col-md-3{
    display: inline-block !important;
    float: left !important;
  }
  .memo-content-section .col-md-4{
    float: left !important;
    display: inline-block !important;
  }
  .memo-content-section .col-md-8{
    width: 50.333333% !important;
    float: left !important;
    display: inline-block !important;
  }
  /* height: 200px;
  width: 300px; */
}
</style>
