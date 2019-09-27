<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Generatememo */

$this->title = 'Update Camera Service';
$this->params['breadcrumbs'][] = ['label' => 'Camera Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generate-memo-update">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
    <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
    <?php
    $breadcrumb[]  = ['label' => 'Camera Service', 'url' => ['/camera-service-request/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camera Service'), 'url' => ['index']];
    if($model->id){
    $this->title =  'Update';
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
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Update Info</a></li>
        <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Service Details
        </a></li>
        <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Assign Technician
        </a></li>
      </ul>
      <div class="tab-content" style="margin-left: 18px;">
          <div class="tab-pane active" id="tab_1">
            <?php
                Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-update-camera-service','options'=>['data-loader'=>'.preloader']]);
             ?>
             <?= $this->render('update',
                 [
                   'model' => $model,
                   'modelImage' => $modelImage,
                  ]);
              ?>
              <?php
                Pjax::end();
              ?>
          </div>
          <div class="tab-pane" id="tab_2">
            <?php
                Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-camera-service-details','options'=>['data-loader'=>'.preloader']]);
                foreach ($modelCameraServiceAssignment as $modelCameraServiceAssignment)
                {
             ?>
             <?= $this->render('camera-service-details',
                 [
                   // 'model' => $model,
                   'modelCameraServiceAssignment' => $modelCameraServiceAssignment,
                  ]);
              ?>
              <?php
            }
                Pjax::end();
              ?>
        </div>
        <div class="tab-pane" id="tab_3">
          <?php
              Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-assign-technician','options'=>['data-loader'=>'.preloader']]);
           ?>
           <?php
           // echo $this->render('assign-technician',
           //     [
           //       'model' => $model,
           //       'modelCameraServiceAssignment' => $modelCameraServiceAssignment,
           //      ]);
            ?>
            <?php
              Pjax::end();
            ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>
