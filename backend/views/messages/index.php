<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use backend\models\District;
use backend\models\Account;
 use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SbhrmAssetAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $newDataProvider = $dataProvider;
// $newDataProvider->pagination = false;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$scrollingTop = 30;
$this->title = Yii::t('app', 'Push Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Push Notifications</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Push Notifications';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <p>
        <?= Html::a(Yii::t('app', 'Create Notification'), ['send'], ['class' => 'btn btn-success']) ?>
    </p>
      
</div>
   <br>
   <div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-notification-list', 'options'=>['data-loader'=>'.preloader']]); ?>
   <?php
   $columns = [
   'message',
   'message_ml',
            [
                    'attribute' =>  'type',
                    'label' =>'Type',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $type = '';
                      if($model->lsgi_id)
                      {
                        $type='Lsgi';
                      }elseif($model->hks_id)
                      {
                        $type='Hks';
                      }elseif($model->ward_id)
                      {
                        $type='Ward';
                      }
                      return $type;
                    },
                  ],
                  [
                    'attribute' =>  'lsgi_id',
                    'label' =>'Lsgi',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkLsgi)?$model->fkLsgi->name:'';
                    },
                  ],
                  [
                    'attribute' =>  'hks',
                    'label' =>'Harithakarmasena',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkHks)?$model->fkHks->name:'';
                    },
                  ],
                  [
                    'attribute' =>  'ward',
                    'label' =>'Ward',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkWard)?$model->fkWard->name:'';
                    },
                  ],
                  ];
  
   echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => $columns,
    // 'summary'=>'',
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
]);?>
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
