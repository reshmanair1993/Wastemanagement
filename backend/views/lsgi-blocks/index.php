<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsgiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Lsgi Blocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="isgi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-lsgi-blocks-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('lsgi-blocks-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif;?>
    <?php
    $columns = [
            [
                    'attribute' => 'name',
                    'label' =>'State',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('lsgi-blocks-update')||$userRole=='super-admin'):
                      return Html::a($model->name,['lsgi-blocks/update?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $this->name;
                    endif;
                    },
                  ],
            'code',
            'assemblyConstituency.name',
            
           
       ];if(Yii::$app->user->can('lsgi-blocks-delete-lsgi')||$userRole=='super-admin'){
        $columns2 = [             
                    [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['lsgi-blocks/delete-lsgi','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-lsgi-blocks-list',$page,function() {
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
