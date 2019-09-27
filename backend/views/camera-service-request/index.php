<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

$scrollingTop = 30;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Camera Service Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="camera-service-request-index">

  <div class="row bg-title">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    </div>
    <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
        <?php
        $breadcrumb[] = ['label' => $this->title,];

        ?>
        <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
      </div>
  </div>
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-camera-service-request-list', 'options'=>['data-loader'=>'.preloader']]); ?>

    <p>
        <?= Html::a('Create Camera Service Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout' => '{items}',
        // 'layout' => "{items}\n{pager}",
        'columns' => [
            [
              'attribute' => 'name',
              'label' =>'Camera',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $camera = $model->fkCamera->name;
                if($camera)
                  return Html::a(ucfirst($camera),['camera-service-request/update?id='.$model->id],['data-pjax'=>0]);
                else
                  return 'Nil';
              },
            ],
            [
              'label' =>'Service',
              'format' => 'raw',
              'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $service = $model->fkCameraService->name;
                if($service)
                  return ucfirst($service);
                else
                  return 'Nil';
              },
            ],
            [
              'label' =>'Request Date',
              'format' => 'raw',
              'value'=>function ($model) {
                if($model->request_date){
                  $requested_date = Yii::$app->formatter->asTime($model->request_date, 'dd-MM-yyyy hh:mm:ss');
                  return $requested_date;
                }
                else{
                  return 'Nil';
                }
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
                $url =  Yii::$app->urlManager->createUrl(['camera-service-request/delete-camera-service-request','id'=>$model->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-camera-service-request-list',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
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
    ]); ?>
    <?phpPjax::end();?>
</div>
