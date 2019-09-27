<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model backend\models\Incident */

$this->title = Yii::t('app', 'Incident Details');

$modelVideo       = $model->fkVideo;
$modelImage        = $model->fkImage;
$incidentTypeName = Html::encode($modelIncidentType);
$cameraId         = $model->camera_id;
$wardName = $model->getWard($cameraId);
$modelCamera = $model->fkCamera;
$duration = $model->duration;
$capturedAt = $model->captured_at;
// print_r($capturedAt);exit;
?>
<div class="incident-view">
  <div class="row bg-title">
    <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
      <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Incident List', 'url' => ['/camera/incident-list','id'=>$model->camera_id]];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Incidents'), 'url' => ['index']];
  if($model->id){
     $this->title =  'Incident Details';
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
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-incident', 'options'=>['data-loader'=>'.preloader']]); ?>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12 video-section">
      <?php
          if($modelVideo){
            $url = $modelVideo->url;
      ?>
      <video controls>
      <source src="<?php echo $modelVideo->getFullUrl($url);?>" type="video/mp4">
      </video>
      <?php
          }

      ?>
    </div>
  </div>
  <div class="incident-content-section">
    <div class="col-lg-12 col-md-12 col-sm-4 col-12">
      <div class="row col-lg-12 col-md-12 col-sm-4 col-12">
        <h3><b><?=$incidentTypeName?></b></h3>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-4 col-12"  style="margin-bottom:10px">
        <?php
        if($wardName)
         echo $wardName->name;
        ?>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-4 col-12"  style="margin-bottom:10px">
        <?php
        if($modelCamera)
          echo $modelCamera->name;
         ?>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-4 col-12"  style="margin-bottom:10px">
        <?php
          if($capturedAt)
            echo date("d-m-Y g:i a", strtotime($capturedAt));
          else
            echo null;
          ?>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-4 col-12"  style="margin-bottom:10px">
          <?php
            $modelIncidentMetas = $model->getIncidentMeta($model->id);
            if($modelIncidentMetas){
            foreach ($modelIncidentMetas as $modelIncidentMeta) {
          ?>
            <?=$modelIncidentMeta->incident_key.':'?>
            <?=$modelIncidentMeta->value?>
        <?php
           }
          }
        ?>
        </div>
        <!-- <a href="https://www.diffchecker.com/diff" target="_blank">click</a> -->
        <?php
          $incident_url = $incident_base.$model->id;
         ?>
        <div class="row col-lg-12 col-md-12 col-sm-4 col-12" style="margin-bottom:10px">
         Publicly Shared Url :
         <a target="_blank" href="<?=$incident_url ?>" ><?=$incident_url?></a>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <a href=""  onclick="isApproved()"  class="approve-btn btn btn-success" id="approve">
        <?php if($model->is_approved == 0){?>
          <img src="" alt="" id="approve-img"> Approve
      <?php }else{?>
        <img src="" alt="" id="dis-approve-img"> Disapprove
      <?php }?>
        </a>
    </div>
    <?php
    if($model->is_approved == 1){
    if(!$modelMemo){
     ?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
          <?= Html::a('Generate Memo', ['generate-memo', 'id' => $model->id], ['class'=>'btn btn-success']) ?>
      </div>
      <?php
    }else{
      ?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
          <?= Html::a('View Memo', ['memos/view-memo','id' =>$model->id], ['class'=>'btn btn-success']) ?>
      </div>
  <?php }} ?>
  </div>
  <?php Pjax::end();?>
</div>
<?php
// $disApprove = Html::tag('button', 'Approve', ['class' => "approve-btn btn btn-success"]);
// $approve = Html::tag('button', 'DisApprove', ['class' => "approve-btn btn btn-success"]);
// // print_r($disApprove);exit;
?>
<script>

  function isApproved() {
    $.ajax({
      type: "POST",
      url:"<?=Url::to(['incidents/approve-incident'])?>",
      data: {id:<?=$model->id?>},
        dataType: "json",

      success:function(response) {
        console.log(response);
      }
    });
  }

  </script>
  <?php
    $title = isset($title)?$title:'Success';
    $type = isset($type)?$type:'success';
    $message = isset($message)?$message:'Memo has been added successfully';
    $title = Html::encode(trim($title));
    $message = Html::encode(trim($message));
    $title =  $title;
    $message =  $message; //but need to escape apppstrope
    if ($showSuccess == 1):
      $this->registerJs("
      swal({title:'$title',text: '$message', type:'$type'});
      ");
    endif ;
    // print_r($approveSuccess);exit;
    if ($approveSuccess == 1):
      $this->registerJs("
      swal({title:'Success',text: 'Approved successfully', type:'$type'});
      ");
    // else:
    //     $this->registerJs("
    //     swal({title:'Success',text: 'DisApproved successfully', type:'$type'});
    //     ");
    endif ;
  ?>
