<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;

$scrollingTop = 30;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Memo Penalties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memo-penalty-index">

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
                          'id' =>'pjax-memo-penalty', 'options'=>['data-loader'=>'.preloader']]); ?>

    <p>
        <?= Html::a('Create Memo Penalty', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [

            [
              'attribute' => 'name',
              'label' =>'Memo Type',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                return Html::a($model->getMemoType($model->memo_type_id),['memo-penalties/update?id='.$model->id],['data-pjax'=>0]);
              },
            ],
            [
              'label' =>'Lsgi',
              'format' => 'raw',
              'value'=>  function ($model) {
                $modelLsgi = $model->getLsgi($model->lsgi_id);
                if($modelLsgi)
                  return $modelLsgi->name;
              },
            ],
            [
              'label' =>'Amount',
              'format' => 'raw',
              'value'=>  'amount',
            ],
            [
               'attribute' => 'delete',
               'label' =>'Delete',
               'contentOptions'=>[ 'style'=>'width: 50px'],
               'format' => 'raw',
               'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $page = $page;
                $url =  Yii::$app->urlManager->createUrl(['memo-penalties/delete-memo-penalty','id'=>$model->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-memo-penalty',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
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
      ]);
?>
    <?php Pjax::end(); ?>
    <?php
      $title = isset($title)?$title:'Success';
      $type = isset($type)?$type:'success';
      $message = isset($message)?$message:'Memo Penalty has been added successfully';
      $title = Html::encode(trim($title));
      $message = Html::encode(trim($message));
      $title =  $title;
      $message =  $message; //but need to escape apppstrope
      if ($showSuccess == 1):
        $this->registerJs("
        swal({title:'$title',text: '$message', type:'$type'});
        ");
      endif ;
      if ($updateSuccess == 1):
        $this->registerJs("
        swal({title:'Success',text: 'Memo Penalty has been updated successfully', type:'$type'});
        ");
      endif ;
    ?>
</div>
