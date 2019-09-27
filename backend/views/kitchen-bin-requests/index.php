<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use backend\models\Ward;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$scrollingTop = 30;
$this->title = Yii::t('app', 'Kitchen Bin Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Kitchen Bin Requests</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Kitchen Bin Requests';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
             <p>
        <?= Html::a(Yii::t('app', 'Create Kitchen Bin Request'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search  m-r-10']]);?>
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
            $ward=Ward::getWardList();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($model, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
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
                      'id' =>'pjax-kitchen-bin-requests-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php 
    $columns =  [
            [
                    'attribute' => 'house_owner_name',
                    'label' =>'House Owner Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a($model->house_owner_name,['kitchen-bin-requests/update?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],
                    'house_owner_name',
                    'house_number',
                    'residence_association',
                    // 'fkAssociation.name',
                    'association_number',
                    [
                    'attribute' => 'ward',
                    'label' =>'Ward',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkWard->name_en)?$model->fkWard->name_en:'';
                    },
                  ],
                    'contact_no',
                    'address:ntext',
                    // 'ownership_of_house',
                    'owner_name',
                    'contact_number_owner',
                    ];
            if(Yii::$app->user->can('kitchen-bin-requests-toggle-status-approved')){
        $columns2 = [             
                                     [

                  'format'=>'raw',
                  'attribute' => 'is_approved',
                  // 'label' => 'Active/Inactive' ,
                  'label' => 'Approve' ,
                  'encodeLabel' => false,
                  'value' => function ($model)
                  {
                    $statusLabels = [
                     ['label'=>'Approve','cssClass'=>'label-danger'],
                     // ['label'=>'Inactive','cssClass'=>'label-success'],
                     ['label'=>'Approved','cssClass'=>'label-success'],
                   ];
                    $status = (int)$model->approval_status;
                    $status = $status != -1?$status:1;
                    $labelCur = $statusLabels[$status];
                    $cssClass = $labelCur['cssClass'];
                    $class = "label $cssClass";
                    $label = $labelCur['label'];
                    $page = isset($_GET['page'])?trim($_GET['page']):0;
                    $user = Yii::$app->user->identity;
                    $url = Yii::$app->urlManager->createUrl(['kitchen-bin-requests/toggle-status-approved','id'=>$model->id]);
                    if($model->approval_status==0){
                    return Html::a("<a style='color: #fff' onclick='deleteItem(\"$url\",\"#pjax-kitchen-bin-requests-list\",$page)'  id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }else
                {
                    return Html::a("<a style='color: #fff' id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }
                    
                  },
                   ],
              ];
    }else
    {
      $columns2 = [];
    }
          if(Yii::$app->user->can('kitchen-bin-requests-delete-request')){
        $columns3 = [             
                     [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['kitchen-bin-requests/delete-request','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-kitchen-bin-requests-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
              ];
    }else
    {
      $columns3 = [];
    }
    $columns = array_merge($columns,$columns2,$columns3);
    $pdfHeader = [
  'L' => [
    'content' => 'Kitchen Bin Requests-'.date('d-M-Y'),
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
    'content' => 'Kitchen Bin Requests-'.date('d-M-Y'),
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
    ?>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
        'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Kitchen Bin Request-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Kitchen Bin Request -'.date('d-M-Y')],
                   // GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Kitchen Bin Request -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Kitchen Bin Request -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Kitchen Bin Request -'.date('d-M-Y')],
                   GridView::PDF => [
    'filename' => 'Kitchen Bin Requests -'.date('d-M-Y'),
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
        'title' => 'Kitchen Bin Requests -'.date('d-M-Y'),
        'subject' => 'Kitchen Bin Requests -'.date('d-M-Y'),
        'keywords' => 'pdf, export, other, keywords, here'
      ],
    ]
  ],
                ],
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
    ]); ?>
  <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 ",View::POS_END);
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
