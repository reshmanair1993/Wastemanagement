<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\web\View;
use backend\models\Incident;
use backend\models\Memo;
use kartik\grid\GridView;
use backend\models\PaymentCounter;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$scrollingTop = 30;
$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
$modelUser = Yii::$app->user->identity;
$userRole  = $modelUser->role;
?>
<div class="payment-report-index">

  <div class="row bg-title">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    </div>
    <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
        <?php
        $breadcrumb[] = ['label' => $this->title,];

        ?>
        <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
      </div>
  </div>
  <div class="col-lg-12 col-md-12 colsm-6 col-6">

</div>

    <div class="row">
      <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
        <div class="form-group" style=" margin-top: -13px;">
          <?php
          $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

          $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Counters..','name'=>'counter'];
          $key =  isset($_POST['counter'])?$_POST['counter']:'';

          if(isset($key)) {
          $option = $key;
          $options['options'] = [$option => ['selected'=>'selected']];
          }
          $counter = PaymentCounter::getPaymentCounter();
          $listData = ArrayHelper::map($counter, 'id', 'name');

          echo $form->field($modelMemo, 'payment_counter_id')->dropDownList($listData, $options)->label(false)?>
        </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
        <div class="form-group" style=" margin-top: -13px;">
          <?php
          $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

          $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Payment Counter Admin..','name'=>'user'];
          $key =  isset($_POST['user'])?$_POST['user']:'';
          $counter_id =  isset($_POST['counter'])?$_POST['counter']:'';

          if(isset($key)) {
          $option = $key;
          $options['options'] = [$option => ['selected'=>'selected']];
          }
          // print_r($counter_id);exit;
          // $user = Memo::getPaymentCounterAdmin();
          $user = Memo::findPaymentCounterAdmin($counter_id);
          $listData = ArrayHelper::map($user, 'id', 'username');

          echo $form->field($modelMemo, 'payment_counter_account_id')->dropDownList($listData, $options)->label(false)?>
        </div>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
    <br>
    <div class="row">
   <div class="col-md-12">
     <div class="white-box">
       <div class="scrollable">
         <div class="table-responsive">
   <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                       'id' =>'pjax-customers-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout' => '{items}',
        'columns' => [

            // [
            //   'attribute' => 'name',
            //   'label' =>'Name',
            //   'contentOptions'=>[ 'style'=>'width: 250px'],
            //   'format' => 'raw',
            //   'value'=>function ($model) {
            //     $modelWard = $model->getWard($model->camera_id);
            //     if($modelWard)
            //       return $modelWard->name;
            //   },
            // ],
            [
                'attribute' => 'name',
                'label' => 'Memo'
            ],
            // [
            //   // 'attribute' => 'id',
            //   'label' =>'Incidents',
            //   'contentOptions'=>[ 'style'=>'width: 250px'],
            //   'format' => 'raw',
            //   'value'=>function ($model) {
            //     $from   = Yii::$app->session->get('start');
            //    $to     = Yii::$app->session->get('end');
            //     $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
            //     $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            //
            //     $modelIncident = Incident::getIncidentsCount($model->id,$from,$to);
            //     if($modelIncident)
            //       return $modelIncident;
            //       else {
            //         return 'Nil';
            //       }
            //   },
            // ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
          // 'beforeHeader'=>[
          //     [
          //         'columns'=>[
          //             ['content'=>'Header Before 1', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
          //             ['content'=>'Header Before 2', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
          //             ['content'=>'Header Before 3', 'options'=>['colspan'=>3, 'class'=>'text-center warning']],
          //         ],
          //         'options'=>['class'=>'skip-export'] // remove this row from export
          //     ]
          // ],
          'toolbar' =>  [
              [
              // 'content'=>
              //     Html::button('&lt;i class="glyphicon glyphicon-plus">&lt;/i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
              //     Html::a('&lt;i class="glyphicon glyphicon-repeat">&lt;/i>', ['grid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
              ],
              '{export}',
              '{toggleData}'
          ],
          'pjax' => true,
          'bordered' => true,
          'striped' => false,
          'condensed' => false,
          'responsive' => true,
          'hover' => true,
          'floatHeader' => true,
          'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
          'showPageSummary' => true,
          'panel' => [
              'type' => GridView::TYPE_PRIMARY
          ],
      ]);
    ?>
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
   Pjax::end();?>
  </div>
  </div>
  </div>
  </div>
</div>
