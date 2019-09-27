<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use backend\models\Ward;
use backend\models\Camera;
use backend\models\Account;
use backend\models\MonitoringGroup;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Incidents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-index">
  <div class="row bg-title">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
      </div>
      <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
        <?php
        $breadcrumb[] = ['label' => $this->title,];

        ?>
        <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
      </div>
      <?php if($status == 0){ ?>
      <div class="col-lg-12 col-sm-12 col-md-6 col-xs-12">
        <div class="col-lg-4 col-sm-4 col-md-6 col-xs-12">
          <p>
          <?php //echo Html::a(Yii::t('app', 'Create Incident'), ['create'], ['class' => 'btn btn-success']) ?>
          </p>
        </div>

        <div class="col-lg-8 col-sm-8 col-md-6 col-xs-12">
          <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
            <?php
            // print_r($associations);exit;
              if(!isset($associations['lsgi_id'])):?>
          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <label>Lsgi</label>
            <div class="form-group" style=" margin-top: -13px;">
              <?php
              $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
              $options = ['class'=>'form-control btn-behaviour-filter','id'=>'lsgi-id','prompt' => 'Lsgi..','name'=>'lsgi'];
              $key =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

              if(isset($key)) {
              $option = $key;
              $options['options'] = [$option => ['selected'=>'selected']];
              }

              $lsgi=Account::getLsgi();
              $listData=ArrayHelper::map($lsgi, 'id', 'name');

              echo $form->field($modelIncident, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(!isset($associations['ward_id'])):?>
          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <label>Ward</label>
            <div class="form-group" style=" margin-top: -13px;">
              <?php
              $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
              $options = ['class'=>'form-control btn-behaviour-filter','id'=>'ward-id','prompt' => 'Ward..','name'=>'ward'];
              $key =  isset($_POST['ward'])?$_POST['ward']:'';
              $lsgi_id =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

              if(isset($key)) {
              $option = $key;
              $options['options'] = [$option => ['selected'=>'selected']];
              }
              if(isset($associations['lsgi_id'])){
                  $lsgi_id = $associations['lsgi_id'];
                  $ward = Camera::getWards($lsgi_id);
              }
              else{
                $ward = Camera::getWards($lsgi_id);
              }
              // $ward=Camera::getWards($lsgi_id);
              $listData=ArrayHelper::map($ward, 'id', 'name');

              echo $form->field($modelIncident, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(!isset($associations['group_id'])):?>
          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <label>Group</label>
            <div class="form-group" style=" margin-top: -13px;">
              <?php
              $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

              $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Group..','name'=>'group'];
              $key =  isset($_POST['group'])?$_POST['group']:'';

              if(isset($key)) {
              $option = $key;
              $options['options'] = [$option => ['selected'=>'selected']];
              }
              $modelUser  = Yii::$app->user->identity;
              $group=MonitoringGroup::getGroup($modelUser->id);
              $listData=ArrayHelper::map($group, 'id', 'name');
              echo $form->field($modelIncident, 'group_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
    <?php } ?>
    </div>
     <br>
     <div class="row">
    <div class="col-md-12">
      <div class="white-box">
        <div class="scrollable">
          <div class="table-responsive">
    <!-- <div class="incident-list-section"> -->
      <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-incident-list', 'options'=>['data-loader'=>'.preloader']]); ?>
      <?=ListView::widget(
      [
      'dataProvider' => $dataProvider,
      'itemView' => 'incident-single',
      'summary' => "",
      ]);
      ?>
      <?php Pjax::end();?>
  </div>
  <?php
  $this->registerJs("
  $('.btn-behaviour-filter').on('change', function() {
  $('.search-form').submit();

  });
  $('.datepicker').datepicker({
            orientation:'top',
            format:'dd-mm-yyyy',
            autoclose:true,
            todayHighlight:true,
        });
  ",View::POS_END);
  ?>
</div>
</div>
</div>
</div>
</div>
