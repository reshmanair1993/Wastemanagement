<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
$this->title = Yii::t('app', 'Green Technician');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

  <h1><?= Html::encode($this->title) ?></h1>
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-gt-list', 'options'=>['data-loader'=>'.preloader']]); ?>
  <?php if(Yii::$app->user->can('create-green-technician')||$userRole=='super-admin'):?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Green Technician'), ['create-green-technician'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif;?>
    <?php 
        $columns = [ 
              [
                    'attribute' => 'username',
                    'label' =>'User Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('update-green-technician')||$userRole=='super-admin'):
                      return Html::a($model->username,['account/view-green-technician?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->username;
                    endif;
                    },
                  ],
                  [
                    'attribute' => 'fkPerson.first_name',
                    'label' => 'Name'
                  ],
                  [
                    'attribute' => 'fkPerson.phone1',
                    'label' => 'Phone'
                  ],
                  [
                    'attribute' => 'fkLsgi.name',
                    'label' => 'Lsgi'
                  ],
                  [
                    'attribute' => 'fkGreenActionUnit.name',
                    'label' => 'Green Action Unit'
                  ],
                  // [
                  //   'attribute' => 'is_banned',
                  //   'label' => 'Is Banned'
                  // ],
    ];
        if(Yii::$app->user->can('delete-green-technician')||$userRole=='super-admin'){
         $columns2 = [             
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['account/delete-account','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-gt-list',$page,function() {
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
