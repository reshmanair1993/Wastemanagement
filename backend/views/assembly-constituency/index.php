<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\Assemblyconstituency_typeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Assembly Constituencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assembly-constituency_type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-assembly-constituency-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('assembly-constituency-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Assembly constituency'), ['create'], ['class' => 'btn btn-success']) ?>
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
                      if(Yii::$app->user->can('assembly-constituency-update')||$userRole=='super-admin'):
                      return Html::a($model->name,['assembly-constituency/update?id='.$model->id],['data-pjax'=>0]);
                     else:
                      return $model->name;
                    endif;
                    },
                  ],
                  [
                    'attribute' => 'fkDistrict.name',
                    'label' => 'District'
                  ],
                  [
                    'attribute' => 'fkConstituency1.name',
                    'label' => 'Parliament Constituency 1'
                  ],
                  [
                    'attribute' => 'fkConstituency2.name',
                    'label' => 'Parliament Constituency 2'
                  ],

                 
       ];if(Yii::$app->user->can('assembly-constituency-delete-assembly-constituency')||$userRole=='super-admin'){
        $columns2 = [             
                   [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['assembly-constituency/delete-assembly-constituency','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-assembly-constituency-list',$page,function() {
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
