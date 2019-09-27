<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WasteCollectionIntervalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Waste Collection Intervals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waste-collection-interval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-waste-types-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Waste Collection Interval'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',

            // 'name',
             [
                    'attribute' => 'name',
                    'label' =>'Interval',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a($model->name,['waste-collection-intervals/update?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],
            'fkCategory.name',
            // 'waste_category_id',
            // 'building_type_available',
            // 'is_public',
            //'sort_order',
            //'status',
            //'created_at',
            //'modified_at',
             [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['waste-collection-intervals/delete-waste-type','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-waste-types-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
