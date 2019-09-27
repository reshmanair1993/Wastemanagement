<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ParliamentConstituencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Parliament Constituencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parliament-constituency-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-parliament-constituency-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('parliament-constituency-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Parliament Constituency'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif;?>
    <?php
    $columns = [
            [
                    'attribute' => 'name',
                    'label' =>'Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('parliament-constituency-update')||$userRole=='super-admin'):
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a($model->name,['parliament-constituency/update?id='.$model->id],['data-pjax'=>0]);
                      else:
                        return $model->name;
                      endif;
                    },
                  ],
                  [
                    'attribute' => 'constituency_type',
                    'label' =>'Constituency type',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      if($model->constituency_type==1)
                      return 'Rajya Sabha';
                  else
                    return 'Lok Sabha';
                    },
                  ],
                  'state.name',

            
        ];if(Yii::$app->user->can('parliament-constituency-delete-parliament-constituency')||$userRole=='super-admin'){
        $columns2 = [             
                   [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['parliament-constituency/delete-parliament-constituency','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-parliament-constituency-list',$page,function() {
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
