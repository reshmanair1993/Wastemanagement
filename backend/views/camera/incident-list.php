<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use backend\models\Ward;
use backend\models\Account;
use backend\models\MonitoringGroup;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Camera Incidents';
$this->params['breadcrumbs'][] = ['label' => 'Camera', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Incident Details';
?>
<div class="incident-index">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
        $breadcrumb[]  = ['label' => 'Camera', 'url' => ['/camera/index']];
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camera'), 'url' => ['index']];
        $breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
     ?>
     <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
  </div>
  </div>
     <br>
     <div class="row">
    <div class="col-md-12">
      <div class="white-box">
        <div class="scrollable">
          <div class="table-responsive">
    <!-- <div class="incident-list-section"> -->
      <?php Pjax::begin(); ?>
      <?=ListView::widget(
      [
      'dataProvider' => $dataProvider,
      'itemView' => 'incident-single',
      'summary' => "",
      ]);
      ?>
      <?phpPjax::end();?>
  </div>
  <?php
  ?>
</div>
</div>
</div>
</div>
</div>
