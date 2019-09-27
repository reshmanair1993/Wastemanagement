<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Home');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"> Home</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Home';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
       <?php if(Yii::$app->user->can('create')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <p>
        <?= Html::a(Yii::t('app', 'Create Home'), ['create'], ['class' => 'btn btn-success']) ?>
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
                      'id' =>'pjax-account-home-list', 'options'=>['data-loader'=>'.preloader']]); ?>
   <?php 
        $columns= [
              [
                    'attribute' => 'title',
                    'label' =>'Title',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('update-home')||$userRole=='super-admin'):
                      return Html::a($model->title,['account/update-home?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->title;
                    endif;
                    },
                  ],
                  [
                    'attribute' => 'sub_title',
                    'label' => 'Sub Title'
                  ],
                  
        ];

?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
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
