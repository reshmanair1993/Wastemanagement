<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

// use Yii;


/* @var $this yii\web\View */
/* @var $model backend\models\MonitoringGroup */
// foreach($params as $param => $val)
//   ${$param} = $val;

$this->title = Yii::t('app', 'Update Monitoring Group', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="monitoring-group-update">

  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Monitoring Groups', 'url' => ['/monitoring-groups/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monitoring Groups'), 'url' => ['index']];
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

    <div class="group-form">

      <?php $form = ActiveForm::begin(['action' =>['update','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-6 col-12">
        </div>
        <div class="col-lg-10 col-md-10 col-sm-6 col-12">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-12">
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Group name</label>
                <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Group name'])->label(false); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-12">
            <div class="row">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                <label for="eh-first-name">Created Time</label><br>
                <?php
                  echo date("d-m-Y g:i a", strtotime($model->created_at));
                 ?>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                <label for="eh-first-name">Updated Time</label><br>
                <?php
                  echo date("d-m-Y g:i a", strtotime($model->modified_at));
                ?>
              </div>
            </div>
          </div>
        </div>

      <div class="row">
        <div class="col-lg-12 ta-right">
          <a href="<?=Url::to(['/monitoring-groups/index'])?>" class="btn btn-success">cancel</a>
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>

    </div>
    <!-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div> -->
    <?php ActiveForm::end(); ?>
</div>
</div>
<div class="user-camera-form">
  <div class="row">
    <?= $this->render('add_monitoring_group_user', [
        'model' => $model,
        'modelAccount' => $modelAccount,
        'modelMonitoringGroupUser' => $modelMonitoringGroupUser,
        'userDataProvider' => $userDataProvider
    ]) ?>
    <?= $this->render('add_monitoring_group_camera', [
        'model' => $model,
        'modelCamera' => $modelCamera,
        'modelMonitoringGroupCamera' => $modelMonitoringGroupCamera,
        'cameraDataProvider' => $cameraDataProvider
    ]) ?>

  </div>
</div>
<?php
  $title = isset($title)?$title:'Success';
  $type = isset($type)?$type:'success';
  $message = isset($message)?$message:'User has been added successfully';
  $title = Html::encode(trim($title));
  $message = Html::encode(trim($message));
  $title =  $title;
  $message =  $message; //but need to escape apppstrope
  // print_r($showSuccess);exit;
  if ($userSuccess == 1):
    $this->registerJs("
    swal({title:'$title',text: '$message', type:'$type'});
    ");
  endif ;
  if ($cameraSuccess == 1):
    $this->registerJs("
    swal({title:'Success',text: 'Camera has been added successfully', type:'$type'});
    ");
  endif ;
?>
</div>
