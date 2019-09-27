<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LsgiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Lsgis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lsgi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-lsgis-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('lsgis-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif;?>
    <?php
    $columns = [
            // ['class' => 'yii\grid\SerialColumn'],
           [
                    'attribute' => 'name',
                    'label' =>'Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('lsgis-view')||$userRole=='super-admin'):
                      return Html::a($model->name,['lsgis/view?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->name;
                    endif;
                    },
                  ],
            'fkBlock.name',
			'fkLsgiType.name',
           
         ];
         if(Yii::$app->user->can('lsgis-performance-evaluation')||$userRole=='super-admin'){
        $columns3 = [             
                    [
                    'attribute' => 'name',
                    'label' =>'HKS Evaluation Configuration',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('lsgis-performance-evaluation')||$userRole=='super-admin'):
                      return Html::a('View',['lsgis/performance-evaluation?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return '---';
                    endif;
                    },
                  ],
              ];
    }else
    {
      $columns3 = [];
    }if(Yii::$app->user->can('lsgis-delete-lsgi')||$userRole=='super-admin'){
        $columns2 = [             
                    [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['lsgis/delete-lsgi','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-lsgis-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
              ];
    }else
    {
      $columns2 = [];
    }
    $columns = array_merge($columns,$columns3);
    $columns = array_merge($columns,$columns2);
    

?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
