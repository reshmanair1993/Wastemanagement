<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generate Chalans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generate-chalan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [

            [
              'attribute' => 'name',
              'label' =>'Name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                return Html::a($model->name,['chalans/update?id='.$model->id],['data-pjax'=>0]);
              },
            ],
            [
              'label' =>'Email',
              'format' => 'raw',
              'value'=>  'email',
            ],
            [
              'label' =>'Subject',
              'format' => 'raw',
              'value'=>  'subject',
            ],
            [
              'label' =>'Description',
              'format' => 'raw',
              'value'=>  'description',
            ],
            [
              'label' =>'Amount',
              'format' => 'raw',
              'value'=>  'amount',
            ],
            [
              'label' =>'Incident',
              'format' => 'raw',
              'value'=> function ($model) {
                $modelIncident = $model->getIncident($model->incident_id);
                return $modelIncident->name;
              },
            ],
            [
               'attribute' => 'delete',
               'label' =>'Delete',
               'contentOptions'=>[ 'style'=>'width: 50px'],
               'format' => 'raw',
               'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $page = $page;
                $url =  Yii::$app->urlManager->createUrl(['monitoring-groups/delete-group','id'=>$model->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-group-list',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],

    ]); ?>
    <?php Pjax::end(); ?>
</div>
