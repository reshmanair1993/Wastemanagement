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
use backend\models\CustomerNew;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SbhrmAssetAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $newDataProvider = $dataProvider;
// $newDataProvider->pagination = false;
$modelCustomer = new CustomerNew;
$scrollingTop = 10;
$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Ward Wise Survey Count</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Ward Wise Survey Count';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'ward-wise-count','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search  m-r-10']]);?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        
           <?php  
            // $keyword =  Yii::$app->session->get('name'); 
            $keyword =  isset($_POST['name'])?$_POST['name']:''; 
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
            

      </div>
      <?php if(!isset($associations['district_id'])):?>
      <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'District..','name'=>'district'];
      $key =  isset($_POST['district'])?$_POST['district']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $district=Ward::getDistricts();
            $listData=ArrayHelper::map($district, 'id', 'name');

            echo $form->field($modelCustomer, 'district_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <?php endif;?>
        <?php  if(!isset($associations['lsgi_id'])):?>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Lsgi..','name'=>'lsgi'];
      $key =  isset($_POST['lsgi'])?$_POST['lsgi']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $lsgi=Account::getLsgi();
            $listData=ArrayHelper::map($lsgi, 'id', 'name');

            echo $form->field($modelCustomer, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <?php endif;?>
           <?php  if(!isset($associations['ward_id'])):?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
         <?php  if(isset($associations['ward_id'])&&json_decode($associations['ward_id'])&&sizeof(json_decode($associations['ward_id']))>0):
          
            ?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
        <?php endif;?>
       <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
          <?php 
          // $dateFrom = Yii::$app->session->get('from');
           $from=  isset($_POST['from'])?$_POST['from']:'';
          ?>

            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
      <?php 
      // $to = Yii::$app->session->get('to');
       $to =  isset($_POST['to'])?$_POST['to']:'';
      ?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
   <br>
   <div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php 
  // Pjax::begin(['timeout' => 50000,'enablePushState' => false,
  //                     'id' =>'pjax-customers-list', 'options'=>['data-loader'=>'.preloader']]); 
                      ?>
   <?php
   $columns = [
            [
                    'attribute' => 'name',
                    'label' => 'Ward'
                  ],
                 [
                    'attribute' => 'house',
                    'label' =>'House Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       

                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardHouseCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                   [
                    'attribute' => 'flat',
                    'label' =>'Flat Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardFlatCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'hospital',
                    'label' =>'Hospital Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardHospitalCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'shop',
                    'label' =>'Shop Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardShopCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'office',
                    'label' =>'Office Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardOfficeCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'auditorium',
                    'label' =>'Auditorium Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardAuditoriumCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'market',
                    'label' =>'Market Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardMarketCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'public_place',
                    'label' =>'Public Place Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardPublicPlaceCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'religious_institution',
                    'label' =>'Religious Institution',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = CustomerNew::getWardReligiousCount($model->id,$from,$to);
                      return $count;
                    },
                  ],
                  [
                    'attribute' => 'total',
                    'label' =>'Total Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                      $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d") : '';
                      $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d") : '';
                    
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $houseCount = CustomerNew::getWardHouseCount($model->id,$from,$to);
                      $flatCount = CustomerNew::getWardFlatCount($model->id,$from,$to);
                      $hospitalCount = CustomerNew::getWardHospitalCount($model->id,$from,$to);
                      $shopCount = CustomerNew::getWardShopCount($model->id,$from,$to);
                      $officeCount = CustomerNew::getWardOfficeCount($model->id,$from,$to);
                      $auditoriumCount = CustomerNew::getWardAuditoriumCount($model->id,$from,$to);
                      $marketCount = CustomerNew::getWardMarketCount($model->id,$from,$to);
                      $publicPlaceCount = CustomerNew::getWardPublicPlaceCount($model->id,$from,$to);
                     $religiousCount = CustomerNew::getWardReligiousCount($model->id,$from,$to);
                      $count = $houseCount + $flatCount + $hospitalCount + $shopCount + $officeCount + $auditoriumCount + $marketCount + $publicPlaceCount+$religiousCount; 
                      return $count;
                    },
                  ],
        
        ];
  $pdfHeader = [
  'L' => [
    'content' => 'Ward Wise Report-'.date('d-M-Y'),
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
    'content' => 'Ward Wise Report-'.date('d-M-Y'),
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Ward Wise Report-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Ward Wise Report -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Ward Wise Report -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Ward Wise Report -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Ward Wise Report -'.date('d-M-Y')],
                   GridView::PDF => [
    'filename' => 'Ward Wise Report -'.date('d-M-Y'),
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
        'title' => 'Ward Wise Report -'.date('d-M-Y'),
        'subject' => 'Ward Wise Report -'.date('d-M-Y'),
        'keywords' => 'pdf, export, other, keywords, here'
      ],
    ]
  ],
                ],
    // 'summary'=>'',
    'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
    
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
        $('#w1-togdata-page').on('click', function() {
 location.reload();
 });
 ",View::POS_END);
 // Pjax::end();
 ?>
</div>
</div>
</div>
</div>
</div>
