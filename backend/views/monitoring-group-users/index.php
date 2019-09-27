<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Monitoring Group Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monitoring-group-user-index">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memo'), 'url' => ['index']];
        $breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
     ?>
     <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
  </div>
  </div>
<div class="col-lg-6 col-md-6 col-sm-6 col-12 technician-box">
    <?= $this->render('technician_list', [
        'modelAccount' => $modelAccount,
        'dataProvider' => $dataProvider,
        // 'modelMonitoringGroupUser' => $modelMonitoringGroupUser,
        // 'userDataProvider' => $userDataProvider
    ]) ?>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-12 monitoring-person-box">
  <?= $this->render('monitoring_person_list', [
      'modelAccount' => $modelAccount,
      'monitoringPersonDataProvider' => $monitoringPersonDataProvider,
      // 'modelMonitoringGroupUser' => $modelMonitoringGroupUser,
      // 'userDataProvider' => $userDataProvider
  ]) ?>
</div>
<?php
  $title = isset($title)?$title:'Success';
  $type = isset($type)?$type:'success';
  $message = isset($message)?$message:'Technician has been added successfully';
  $title = Html::encode(trim($title));
  $message = Html::encode(trim($message));
  $title =  $title;
  $message =  $message; //but need to escape apppstrope
  // print_r($showSuccess);exit;
  if ($technicianSuccess == 1):
    $this->registerJs("
    swal({title:'$title',text: '$message', type:'$type'});
    ");
  endif ;
  if ($updateTechnicianSuccess == 1):
    $this->registerJs("
    swal({title:'Success',text: 'Technician has been updated successfully', type:'$type'});
    ");
  endif ;
  if ($monitoringPersonSuccess == 1):
    $this->registerJs("
    swal({title:'Success',text: 'Monitoring Person has been added successfully', type:'$type'});
    ");
  endif ;
  // print_r($updateMonitoringPersonSuccess);exit;
  if ($updateMonitoringPersonSuccess == 1):
    $this->registerJs("
    swal({title:'Success',text: 'Monitoring Person has been updated successfully', type:'$type'});
    ");
  endif ;
  if ($passwordSuccess == 1):
    $this->registerJs("
    swal({title:'Success',text: 'password has been updated successfully', type:'$type'});
    ");
  endif ;
?>
</div>
