<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'QR Code');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">QR Code</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'QR Code';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
       <?php if(Yii::$app->user->can('qr-code-create')||$userRole=='super-admin'):?>
       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::a(Yii::t('app', 'Create QR Code'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
      <?php endif;?>
        <?php if(Yii::$app->user->can('qr-code-view-qr-sheet')||$userRole=='super-admin'):?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::a(Yii::t('app', 'View Printable Sheet'), ['view-qr-sheet'], ['class' => 'btn btn-success','target'=>'_blank']) ?>
    </p>
        </div>
      <?php endif;?>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
          <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-qr-list', 'options'=>['data-loader'=>'.preloader']]); ?>
   <?php
   $columns = [
   				[
                    'attribute' => 'code',
                    'label' =>'Code',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                     // $img = '<img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$model->value.'&choe=UTF-8">';
                     //  return $img;
                       // $path = 'https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$model->value.'&choe=UTF-8';
                       $path = 'https://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl='.$model->value.'&chs=200x200&choe=UTF-8&chld=L|0';
                      $type = pathinfo($path, PATHINFO_EXTENSION);
                      $data = file_get_contents($path);
                      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                      return '<img style="width: 100px;" src="'.$base64.'" />';
                    },
                  ],
                  'value',
                 
        ];
        if(Yii::$app->user->can('qr-code-delete-code')||$userRole=='super-admin'){
        $gridColumns = [
   				
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['qr-code/delete-code','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-qr-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
        ];
}else
    {
      $gridColumns = [];
    }
        $gridColumns = array_merge($columns,$gridColumns);
                
  //   echo ExportMenu::widget([
  //     'dataProvider' => $newDataProvider,
  //     'columns' => $columns,
  //       'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
  //       'target' => ExportMenu::TARGET_BLANK,
  //       'showColumnSelector' => false,
  //       'exportConfig' => [
  //             ExportMenu::FORMAT_TEXT => false,
  //             ExportMenu::FORMAT_HTML => true,
  //           ExportMenu::FORMAT_CSV => false,
  //           ExportMenu::FORMAT_EXCEL => false
  //         ]
  // ]);

    ?>
    <?= GridView::widget([
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'summary' =>'',
        
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
