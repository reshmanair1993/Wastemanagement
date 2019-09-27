<?php
use yii\helpers\Html;
?>
  <div class="incident-list col-md-5th-1 col-sm-4">
<?php
    $modelImage        = $model->fkImage;
    $incidentType     = $model->fkIncidentType;
    if($incidentType)
      $incidentTypeName = $incidentType->name;
    else {
      $incidentTypeName = null;
    }
    $cameraId         = $model->camera_id;
    $modelCamera = $model->fkCamera;
    if($modelCamera)
      $cameraName = $modelCamera->name;
    else {
      $cameraName = null;
    }
    $duration = $model->duration;
?>
<div class="incident-box">
  <div class="incident-image">
  <?php
      if($modelImage){
        $url = $modelImage->uri_full;
  ?>
        <img class="img-thumbnail" src="<?php echo $modelImage->getFullUrl($url);?>" />
  <?php
      }
  ?>
</div>
<div class="incident-content">
  <div class="col-lg-12 col-md-12 col-sm-6 col-12">
    <div class="row"  style="font-size : 13px">
      <br><b><?php echo Html::a($model->getIncidentType($model->incident_type_id),['camera/incident-detail?id='.$model->id],['data-pjax'=>0]);?></b><br>
    </div>
    <div class="row"  style="font-size : 13px">
      <?php
      if($cameraId){
        $wardName = $model->getWardName($cameraId);
        echo $wardName;
      }
      ?>
    </div>
    <div class="row"  style="font-size : 13px">
      <div class="col-lg-6 col-md-6 col-sm-6 col-12" style="padding : 0px">
        <?=$cameraName?>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12" style="padding : 0px">
        <?=$duration?>
      </div>
    </div>
    <div class="row"  style="font-size : 13px">
      <?php
      $capturedAt = $model->captured_at;
      if($capturedAt)
        echo date("d-m-Y g:i a", strtotime($capturedAt));
      else
        echo null;
      ?>
    </div>
  </div>
</div>
</div>
</div>
