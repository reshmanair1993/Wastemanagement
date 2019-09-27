<?php
// use yii2assets\PrintThis;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

// use yii\widget\PrintThis;
// namespace yii2assets\printthis;

  $form = ActiveForm::begin(['action' => ['incident-preview','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  $modelLsgi = $model->getLsgi($model->camera_id);
  $modelVideo       = $model->fkVideo;
  $modelImage        = $model->fkImage;
  $incidentTypeName = Html::encode($modelIncidentType);
  $cameraId         = $model->camera_id;
  $wardName = $model->getWard($cameraId);
  $modelCamera = $model->fkCamera;
  $duration = $model->duration;
  $capturedAt = $model->captured_at;
  $memo = $model->getIncidentMemo($model->id);

  // $modelAuthorizedSignatory = $model->getAuthorizedSignatory($model->lsgi_authorized_signatory_id);
  // $modelIncident = $model->getIncident($model->incident_id);
  // if(isset($modelIncident)?$modelIncident:''){
  //   $modelCamera = $model->getCamera($modelIncident->camera_id);
  //   $modelIncidentName = $model->getIncidentType($modelIncident->id);
  //   $incidentImage = $modelIncident->image_id;
  //   $incidentVideo = $modelIncident->file_video_id;
  //   if($incidentVideo)
  //     $modelVideo        = $model->getVideo($incidentVideo);
  //   if($incidentImage)
  //     $modelImage        = $model->getImage($incidentImage);
  // }
  // $modelMemoType = $model->getMemoType($model->memo_type_id);
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
        <label for="charge">Incident : </label>
        <?php
        echo ucfirst($incidentTypeName); ?>
      </div>
    </div>
  <div class="memo-content-section">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding">
        <label for="charge">Location : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding">
        <?php
        if($wardName)
         echo $wardName->name;
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding">
        <label for="charge">Captured at : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding">
        <p>
        <?php
        if($capturedAt)
          echo date("d-m-Y g:i a", strtotime($capturedAt));
        else
          echo null;
        ?>
      </p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding">
        <label for="charge">Meta Datas : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding">
        <?php
          $modelIncidentMetas = $model->getIncidentMeta($model->id);
          if($modelIncidentMetas){
          foreach ($modelIncidentMetas as $modelIncidentMeta) {
        ?>
          <?php echo ucfirst($modelIncidentMeta->incident_key).':'?>
          <?php echo ucfirst($modelIncidentMeta->value)?><br>
      <?php
    }
        }
      ?>
    </div>
      </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12  field-padding">
        <label for="charge">Proof : </label>
      </div>
      <div class="row">
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
      </div>
    </div>
  </div>
</div>
</div>
<!-- </div> -->
<?php
// print_r($memo->id);exit;
if($model->is_approved == 1){
if(!$modelMemo){
 ?>
  <div class="col-lg-4 col-md-4 col-sm-4 col-12">

  </div>
  <?php
}else{

  ?>
  <div class="col-lg-12 col-md-12 col-sm-6 col-12 field-padding">
      <?= Html::a('View Memo', ['memos/preview','id' =>$memo->id], ['class'=>'btn btn-success']) ?>
  </div>
<?php }} ?>
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
<style media="screen">
@media print {
  background-color: lightgreen;
  /* height: 200px;
  width: 300px; */
}
</style>
