<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dumping Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Dumping Events</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Dumping Events';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
     
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Type..','name'=>'type'];
      $key =  isset($_POST['type'])?$_POST['type']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $type=$model->getTypes();
            $listData=ArrayHelper::map($type, 'id', 'name');

            echo $form->field($model, 'incident_type_id')->dropDownList($listData, $options)->label(false)?>
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
                      'id' =>'pjax-dumping-events-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>"",
        'columns' => [
              [
                    'attribute' => 'cutomer',
                    'label' =>'Customer Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      // return $model->getCustomer();
                      $customerId = isset($model->fkAccount->fkCustomer->id)?$model->fkAccount->fkCustomer->id:null;
                       return Html::a($model->getCustomer(),['customers/view-details?id='.$customerId],['data-pjax'=>0]);
                    },
                  ],
                  [
                    'attribute' => 'fkType.name',
                    'label' => 'Event Type'
                  ],
                  [
                    'attribute' => 'location_name',
                    'label' => 'Location'
                  ],
                  [
                    'attribute' => 'date',
                    'label' =>'Reported Date',
                    'contentOptions'=>[ 'style'=>'width: 150px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->created_at)?date('d-M-Y',strtotime($model->created_at)):null;
                    },
                  ],
                  [
                    'attribute' => 'remarks',
                    'label' => 'Comments'
                  ],
                  [
                    'attribute' => 'image_id',
                    'format' => 'raw',
                    'label' => 'Photo',
                    'value'=>function ($model) {
                       return Html::img($model->getProfileUrl(),
                ['width' => '70px','height'=>'50px']);
                      // return $model->getProfileUrl();
                    }

                  ],
                   
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['dumping-events/delete-dumping-events','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-dumping-events-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
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
