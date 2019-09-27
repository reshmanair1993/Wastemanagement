<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use backend\models\Mrc;
use backend\models\Item;
use yii\helpers\Url;

$scrollingTop = 10;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sales Module');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Sales Module</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Sales Module';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
    <p>
        <?= Html::a(Yii::t('app', 'Sales Module'), ['add-mrc-survey-agency'], ['class' => 'btn btn-success']) ?>
    </p
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['item-mrc/sales-module','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
        <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
          <div class="form-group" style=" margin-top: -13px;">
                 <?php 
          $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

        $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Item..','name'=>'item'];
      $key =  Yii::$app->session->get('item');
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $item=Item::find()->where(['status'=>1])->all();
            $listData=ArrayHelper::map($item, 'id', 'name');

            echo $form->field($model, 'item')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
          <div class="form-group" style=" margin-top: -13px;">
                 <?php 
          $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

        $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'MCF/RRF..','name'=>'mrc'];
      $key =  Yii::$app->session->get('mrc');
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $mrc=Mrc::find()->where(['status'=>1])->all();
            $listData=ArrayHelper::map($mrc, 'id', 'name');

            echo $form->field($model, 'mrc')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
             <div class="col-lg-2 col-sm-6 col-md-6 col-xs-12">
          <?php $from = Yii::$app->session->get('from');?>
            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
      <?php $to = Yii::$app->session->get('to');?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
          <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-item-mrc-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php
   $columns = [
              [
                    'attribute' => 'name',
                    'label' =>'Item',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return Html::a($model->fkItem->name,['item-mrc/update-mrc-survey-agency?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],
                  // [
                  //   'attribute' => 'green_action_unit_id',
                  //   'label' =>'Haritha karma sena',
                  //   'contentOptions'=>[ 'style'=>'width: 250px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //      return $model->fkHks->name;
                  //   },
                  // ],
                  [
                    'attribute' => 'mrc_id',
                    'label' =>'MCF/RRF',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return $model->fkMrc->name;
                    },
                  ],
                  [
                    'attribute' => 'qty',
                    'label' =>'Quantity(In Kg)',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return $model->qty;
                    },
                  ],
                  [
                    'attribute' => 'rate_per_kg',
                    'label' =>'Rate Per Kg',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return $model->rate_per_kg;
                    },
                  ],
                  [
                    'attribute' => 'total',
                    'label' =>'Total',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return $model->total;
                    },
                  ],
                  [
                    'attribute' => 'remarks',
                    'label' =>'Remarks',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       return $model->remarks;
                    },
                  ],
                 
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['item-mrc/delete-item-list','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-item-mrc-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
        ];
    $pdfHeader = [
  'L' => [
    'content' => 'Sales Module Report-'.date('d-M-Y'),
  ],
  'C' => [
    'content' => '',
    'font-size' => 10,
    'font-style' => 'B',
    'font-family' => 'arial',
    'color' => '#333333'
  ],
  'R' => [
    'content' => '',
  ],
  'line' => true,
];

$pdfFooter = [
  'L' => [
    'content' => 'Sales Module Report-'.date('d-M-Y'),
    'font-size' => 10,
    'color' => '#333333',
    'font-family' => 'arial',
  ],
  'C' => [
    'content' => '',
  ],
  'R' => [
    'content' => '',
    'font-size' => 10,
    'color' => '#333333',
    'font-family' => 'arial',
  ],
  'line' => true,
];     
    echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => $columns,
    'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Sales Module Report-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Sales Module Report -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Sales Module Report -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Sales Module Report -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Sales Module Report -'.date('d-M-Y')],
                                    GridView::PDF => [
    'filename' => 'Sales Module Report-'.date('d-M-Y'),
    'config' => [
      'methods' => [
        'SetHeader' => [
          ['odd' => $pdfHeader, 'even' => $pdfHeader]
        ],
        'SetFooter' => [
          ['odd' => $pdfFooter, 'even' => $pdfFooter]
        ],
      ],
      'options' => [
        'title' => 'Sales Module Report-'.date('d-M-Y'),
        'subject' => 'Sales Module Report-'.date('d-M-Y'),
        'keywords' => 'pdf, export, other, keywords, here'
      ],
    ]
  ],
                ],
    // 'summary'=>'',
    'containerOptions' => ['style'=>'overflow: auto'], // only set when 
    'toolbar' =>  [
        [
        ],
        '{export}',
        '{toggleData}'
    ],
    'pjax' => false,
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
