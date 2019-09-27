<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Service;
use backend\models\Schedule;
use backend\models\Ward;
use yii\helpers\Url;
$scrollingTop = 10;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelSchedule = new Schedule;
$this->title = Yii::t('app', 'Complaint Resolution Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Complaint Resolution Report</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Complaint Resolution Report';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['reports/complaint-resolution','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search  m-r-10']]);?>
        <?php if($userRole!='customer'):?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        
           <?php  
           // $keyword =  Yii::$app->session->get('name'); 
           $keyword =  isset($_POST['name'])?$_POST['name']:''; 
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
            

      </div>
    <?php endif;?>
       <?php if($userRole=='super-admin'):?>
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

            echo $form->field($modelSchedule, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
<?php endif;?>
 <?php if($userRole=='supervisor'):?>
  <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','id'=>'ward-id','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      if($userRole=='admin-lsgi'||$userRole=='super-admin'):
      $unit_id =  isset($_POST['unit'])?$_POST['unit']:'';
    else:
      $unit_id = $modelUser->green_action_unit_id;
    endif;
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
           $wards= $modelSchedule->getWardHks($unit_id);
            $listData=ArrayHelper::map($wards, 'id', 'name_en');

            echo $form->field($modelSchedule, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
  <?php endif;?>
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
            $service=Service::getComplaints();
            $listData=ArrayHelper::map($service, 'id', 'name');

            echo $form->field($modelServiceRequest, 'service_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
            <div class="col-lg-3 col-sm-6 col-md-8 col-xs-12">
            <input type="text" name="from" value="<?php if (isset($_POST['from'])) echo $_POST['from']; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker"> <a href="" class="active"></a>
      </div>
      <div class="col-lg-3 col-sm-6 col-md-8 col-xs-12">
            <input type="text" name="to" value="<?php if (isset($_POST['to'])) echo $_POST['to']; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker"> <a href="" class="active"></a>
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
                    'attribute' => 'customer',
                    'label' =>'Customer Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                        return ucwords($model->getCustomer($model->account_id_customer));
                    },
                  ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Id',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->id)?$model->fkAccount->fkCustomer->getFormattedCustomerId($model->fkAccount->fkCustomer->id):null;
                    },
                  ],
                  [
                    'attribute' => 'fkService.name',
                    'label' => 'Service'
                  ],
                  [
                    'attribute' =>  'gt',
                     'label' =>'Green Technician',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkServiceAssignment->fkAccount->fkPerson)?$model->fkServiceAssignment->fkAccount->fkPerson->first_name:'';
                    },
                  ],
                  [
                    'attribute' =>  'association',
                    'label' =>'Association',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->fkAssociation->name)?$model->fkAccount->fkCustomer->fkAssociation->name:'';
                    },
                  ],
                  [
                    'attribute' => 'status',
                    'label' =>'status',
                    'contentOptions'=>[ 'style'=>'width: 150px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getStatus();
                    },
                  ],
                
        ];
  $pdfHeader = [
  'L' => [
    'content' => 'Complaint Resolution Report-'.date('d-M-Y'),
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
    'content' => 'Complaint Resolution Report-'.date('d-M-Y'),
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Complaint Resolution Report-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Complaint Resolution Report -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Complaint Resolution Report -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Complaint Resolution Report -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Complaint Resolution Report -'.date('d-M-Y')],
                    GridView::PDF => [
    'filename' => 'Complaint Resolution Report -'.date('d-M-Y'),
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
        'title' => 'Complaint Resolution Report -'.date('d-M-Y'),
        'subject' => 'Complaint Resolution Report -'.date('d-M-Y'),
        'keywords' => 'pdf, export, other, keywords, here'
      ],
    ]
  ],
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
