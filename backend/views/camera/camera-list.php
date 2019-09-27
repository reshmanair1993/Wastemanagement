<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = Yii::t('app', 'Camera List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="camera-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-camera-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

           [
                    'attribute' => 'name',
                    'label' =>'State',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a($model->name,['camera/update?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],
            'state.name',

            [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['camera/delete-camera','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-camera-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
