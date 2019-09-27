<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Generatememo */

// $this->title = 'Update Generate memo';
// $this->params['breadcrumbs'][] = ['label' => 'Generate memos', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
if($model->id){
   $this->title =  'Memo';
}
else
{
   $this->title =  'Create';
}
// print_r($modelLsgiAddress);exit;
?>
<div class="generate-memo-update">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Memos', 'url' => ['/memos/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memos'), 'url' => ['index']];
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
        <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Preview
        </a></li>
      </ul>
      <div class="tab-content" style="margin-left: 18px;">
          <div class="tab-pane active" id="tab_1">
            <?php
                Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-update-memo','options'=>['data-loader'=>'.preloader']]);
             ?>
             <?= $this->render('update_info',
                 [
                   'model' => $model,
                   'modelIncident' => $modelIncident
                  ]);
              ?>
              <?php
                Pjax::end();
              ?>
          </div>
          <div class="tab-pane" id="tab_2">
          <iframe src="<?=$model->getPreviewUrl()?>" width="100%" height="1800px"></iframe>
        </div>
    </div>
  </div>
</div>
</div>
</div>
