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
use backend\models\Service;
use yii\helpers\Url;
use backend\models\ResidentialAssociation;
$modelCustomer = new Customer;
$modelAccount = new Account;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;

$scrollingTop = 10;
$this->title = Yii::t('app', 'Itemwise Service Pending Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Itemwise Service Pending Report</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Itemwise Service Pending Report';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['reports/itemwise-service-pending','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search  m-r-10']]);?>
   
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      // $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      $key =    Yii::$app->session->get('ward');
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           
            <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Service..','name'=>'service'];
      $key =  isset($_POST['service'])?$_POST['service']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $service=Service::find()->where(['status'=>1])->andWhere(['type'=>1])->all();
            $listData=ArrayHelper::map($service, 'id', 'name');

            echo $form->field($modelService, 'id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
<!--           <?php if($userRole!='supervisor'):?>
 <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Supervisor..','name'=>'supervisor'];
      $sup =  isset($_POST['supervisor'])?$_POST['supervisor']:''; 
      
      if(isset($sup)) { 
        $option = $sup;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $supervisor=$modelAccount->getSupervisors();
            $listData=ArrayHelper::map($supervisor, 'id', 'first_name');

            echo $form->field($modelAccount, 'supervisor_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <?php endif;?>
 <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Green Technician..','name'=>'gt'];
      $sup =  isset($_POST['gt'])?$_POST['gt']:''; 
      
      if(isset($sup)) { 
        $option = $sup;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $gt=$modelAccount->getGt();
            $listData=ArrayHelper::map($gt, 'id', 'first_name');

            echo $form->field($modelAccount, 'gt_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div> -->
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
                    'attribute' =>  'name',
                    'label' =>'Service',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->name;
                    },
                  ],
                  [
                    'attribute' =>  'count',
                    'label' =>'Count',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $from   = Yii::$app->session->get('start');
                      $to     = Yii::$app->session->get('end');
                       $wardId     = Yii::$app->session->get('wardId');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
       

                      return $model->getCountPending($model->id,$from,$to,$type=1,$wardId);
                    },
                  ],        
        ];
   $pdfHeader = [
  'L' => [
    'content' => 'Itemwise Service Pending Report-'.date('d-M-Y'),
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
    'content' => 'Itemwise Service Pending Report-'.date('d-M-Y'),
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
    'columns' => $columns,
    'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Itemwise Service Pending Report-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Itemwise Service Pending Report -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Itemwise Service Pending Report -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Itemwise Service Pending Report -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Itemwise Service Pending Report -'.date('d-M-Y')],
                   GridView::PDF => [
    'filename' => 'Itemwise Service Pending Report -'.date('d-M-Y'),
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
        'title' => 'Itemwise Service Pending Report -'.date('d-M-Y'),
        'subject' =>'Itemwise Service Pending Report -'.date('d-M-Y'),
        'keywords' => 'pdf, export, other, keywords, here'
      ],
    ]
  ],
                ],
    'containerOptions' => ['style'=>'overflow: auto'], 
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
