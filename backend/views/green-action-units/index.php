<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\GreenActionUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Haritha Karma Senas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="green-action-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                        'id' =>'pjax-green-action-unit-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->can('green-action-units-create')||$userRole=='super-admin'):?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Haritha Karma Sena'), ['create'], ['class' => 'btn btn-success']) ?>
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
                      if(Yii::$app->user->can('green-action-units-view-green-action-unit')||$userRole=='super-admin'):
                      return Html::a($model->name,['green-action-units/view-green-action-unit?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->name;
                    endif;
                    },
                  ],
                  'fkLsgi.name',
                  'fkResidenceCategory.name',
                  'contact_person_name',
                  'email',
                  'phone',
          
        ];if(Yii::$app->user->can('green-action-units-delete-green-action-unit')||$userRole=='super-admin'){
        $columns2 = [             
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['green-action-units/delete-green-action-unit','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-green-action-unit-list',$page,function() {
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
