<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildingTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Building Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-building-type-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('building-types-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Building Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif;?>

    <?php
    $columns =  [
            [
                    'attribute' => 'name',
                    'label' =>'Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('building-types-view')||$userRole=='super-admin'):
                      return Html::a($model->name,['building-types/view?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->name;
                    endif;
                    },
                  ],       
        ];
        if(Yii::$app->user->can('building-types-delete-building-type')||$userRole=='super-admin'){
        $columns2 = [             
                   [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['building-types/delete-building-type','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-building-type-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],

              ];
    }else
    {
      $columns2 = [];
    }
    $columns = array_merge($columns,$columns2);

?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
