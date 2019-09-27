<?php
// use yii2assets\PrintThis;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

// use yii\widget\PrintThis;
// namespace yii2assets\printthis;

  $form = ActiveForm::begin(['action' => ['pay-memo','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
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
    <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address" style="margin-bottom:20px">
      <h1><b><?php
      if($modelLsgi)
       echo $modelLsgi->address ?></h1></b>
    </div>
    <div class="row" style="font-size: 18px !important;">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12  field-padding field-head">
        <label for="charge">To: </label>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-6 col-12  field-padding field-content" style="padding-left:50px; margin-top : 0px;">
          <?php
              echo ucfirst($model->name);
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-6 col-12  field-padding field-content" style="padding-left:50px;margin-top: 10px;margin-bottom: 20px;">
          <?php
              echo ucfirst($model->address);
          ?>
        </div>
      </div>
    </div>
    <div class="row col-lg-12 col-md-12 col-sm-12 col-12  field-padding subject" style="margin-bottom:0px;margin-top:10px!important">
      <label for="charge">Subject : </label>
      <?php echo ucfirst($model->subject); ?>
    </div>
  </div>
  <div class="memo-content-section">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding field-head">
        <label for="charge">Memo No : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding field-content">
        <?php
            echo $model->id;
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-12  field-padding">
        <label for="charge">Charge : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-12  field-padding">
        <?php
          if($modelMemoType)
            echo ucfirst($modelMemoType->title);
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-12  field-padding">
        <label for="charge">Reference Rule : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-6 col-12  field-padding">
        <?php
        if($modelMemoType)
          echo ucfirst($modelMemoType->rule_url);
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-12  field-padding">
        <label for="charge">Location : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-6 col-12  field-padding">
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
            if($modelIncident){
                $modelIncidentMetas = $modelIncident->getIncidentMeta($modelIncident->id);
                if($modelIncidentMetas){
                foreach ($modelIncidentMetas as $modelIncidentMeta) {
              ?>
                <?php echo ucfirst($modelIncidentMeta->incident_key).':'?>
                <?php echo ucfirst($modelIncidentMeta->value)?>
              <?php
               }
             }
           }
         ?>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12  field-padding">
        <label for="charge">Proof : </label>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12 proof-section">
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
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12 field-padding">
        <label for="charge">Penal Actions :</label>
      </div>
      </div>
      <div class="row penalty">
      <h2><div class="col-lg-3 col-md-3 col-sm-6 col-12 field-padding">
        <label for="charge">Penality Amount : </label>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-6 col-12 field-padding">
        <b><?php
          echo '₹'.$model->amount.'/-';
        ?></b>
      </div></h2>
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
</div>
<!-- </div> -->
<div class="col-lg-12 col-md-12 col-sm-6 col-12 field-padding">
  <?php if($model->is_paid == 0){?>
    <button type="button" id="check-paid"  onclick="isPaid()"  class="pay-btn btn btn-success">Pay</button>
<?php }else{?>
    <button type="button" id="check-nonpaid" class="pay-btn btn btn-success">Paid</button>
<?php }?>

</div>
<div class="col-lg-12 col-md-12 col-sm-6 col-12 field-padding">
  <?= Html::submitButton('Print', ['class' => 'print-btn btn btn-success','id' => 'print']) ?>
  <?= Html::a('Cancel', ['/memos/search-memo'], ['class'=>'btn btn-success']) ?>
</div>
<?php
ActiveForm::end();
?>
<script>
  function isPaid() {
    var nonPaid = "Html::tag('button', 'Pay', ['class' => 'pay-btn btn btn-success'])";
    var pay = "Html::tag('button', 'Paid', ['class' => 'pay-btn btn btn-success'])";
    $.ajax({
      type: "POST",
      url:"<?=Url::to(['memos/is-paid','id'=>$model->id])?>",
      data:{action:"'.<?=Url::to(['memos/is-paid'])?>.'"},
        dataType: "json",

      success:function(response) {
        console.log(response);
        var src = '';
        if(response.status == 1)
          src = nonPaid;
        else
          src = pay;
        $('#check-paid').attr('src',src);
      }
    });
  }
  </script>
  <?php

  $this->registerJs("
  $('#print').click(function () {
  window.print();
  });
  ",View::POS_END);
  ?>
