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
use backend\models\Customer;
use backend\models\SurveyAgency;
$modelCustomer = new Customer;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SbhrmAssetAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $newDataProvider = $dataProvider;
// $newDataProvider->pagination = false;
$scrollingTop = 10;
$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Survey Count</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Survey Count';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'survey-count','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search m-r-10']]);?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        
           <?php  
            // $keyword =  Yii::$app->session->get('name'); 
            $keyword =  isset($_POST['name'])?$_POST['name']:''; 
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
            

      </div>
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
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
            <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Survey Agency..','name'=>'agency'];
      $key =  isset($_POST['agency'])?$_POST['agency']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $agency=SurveyAgency::find()->where(['status'=>1])->all();
            $listData=ArrayHelper::map($agency, 'id', 'name');

            echo $form->field($modelCustomer, 'agency')->dropDownList($listData, $options)->label('Survey Agency')?>
            </div>
          </div>
       <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
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
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
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
                      // 'id' =>'pjax-customers-list', 'options'=>['data-loader'=>'.preloader']]); 
                      ?>
   <?php
   $columns = [
                  [
                    'attribute' => 'fkPerson.first_name',
                    'label' => 'Surveyor'
                  ],
                  [
                    'attribute' => 'house',
                    'label' =>'House Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                       $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       

                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getHouseCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                   [
                    'attribute' => 'flat',
                    'label' =>'Flat Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                       $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getFlatCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'hospital',
                    'label' =>'Hospital Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getHospitalCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'shop',
                    'label' =>'Shop Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getShopCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'office',
                    'label' =>'Office Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                      $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getOfficeCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'auditorium',
                    'label' =>'Auditorium Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getAuditoriumCount($model->id,$from,$to,$ward);
                      return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'market',
                    'label' =>'Market Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getMarketCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'public_place',
                    'label' =>'Public Place Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                       $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getPublicPlaceCount($model->id,$from,$to,$ward);
                       return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'religious_institution',
                    'label' =>'Religious Institution',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $from =  isset($_POST['from'])?$_POST['from']:'';
                       $to =  isset($_POST['to'])?$_POST['to']:'';
                        $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $count = Customer::getReligiousCount($model->id,$from,$to,$ward);
                      return isset($count)?$count:0;
                    },
                  ],
                  [
                    'attribute' => 'total',
                    'label' =>'Total Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $from =  isset($_POST['from'])?$_POST['from']:'';
                      $to =  isset($_POST['to'])?$_POST['to']:'';
                      $ward =  isset($_POST['ward'])?$_POST['ward']:'';
                      $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                      $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
                    
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $houseCount = Customer::getHouseCount($model->id,$from,$to,$ward);
                      $flatCount = Customer::getFlatCount($model->id,$from,$to,$ward);
                      $hospitalCount = Customer::getHospitalCount($model->id,$from,$to,$ward);
                      $shopCount = Customer::getShopCount($model->id,$from,$to,$ward);
                      $officeCount = Customer::getOfficeCount($model->id,$from,$to,$ward);
                      $auditoriumCount = Customer::getAuditoriumCount($model->id,$from,$to,$ward);
                      $marketCount = Customer::getMarketCount($model->id,$from,$to,$ward);
                      $publicPlaceCount = Customer::getPublicPlaceCount($model->id,$from,$to,$ward);
                      $religiousCount = Customer::getReligiousCount($model->id,$from,$to,$ward);
                     
                      $count = $houseCount + $flatCount + $hospitalCount + $shopCount + $officeCount + $auditoriumCount + $marketCount + $publicPlaceCount+$religiousCount; 
                      return $count;
                    },
                  ],
        
        
        ];
   $pdfHeader = [
  'L' => [
    'content' => 'Survey Count Report-'.date('d-M-Y'),
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
    'content' => 'Survey Count Report-'.date('d-M-Y'),
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Survey Count Report-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Survey Count Report -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Survey Count Report -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Survey Count Report -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Survey Count Report -'.date('d-M-Y')],
                    GridView::PDF => [
    'filename' => 'Survey Count Report -'.date('d-M-Y'),
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
        'title' => 'Survey Count Report -'.date('d-M-Y'),
        'subject' => 'Survey Count Report -'.date('d-M-Y'),
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
