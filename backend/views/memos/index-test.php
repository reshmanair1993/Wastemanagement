<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Memos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generate-memo-index">

  <div class="row bg-title">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    </div>
    <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
        <?php
        $breadcrumb[] = ['label' => $this->title,];

        ?>
        <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
      </div>
  </div>
  <div class="col-lg-12 col-md-12 colsm-6 col-6">
  <div class="col-lg-6 col-md-6 colsm-6 col-6">
    <?php $form = ActiveForm::begin(['action' => Url::to(['index']), 'options' => ['', 'data-pjax' => true, 'class' => 'search-form hidden-sm hidden-xs m-r-10']]);?>
    <div class="col-lg-6 col-md-6 colsm-6 col-6">
      <!-- <label for="labourer-d-o-b">Date of Birth</label> -->
      <?= $form->field($modelMemo, 'date_from')->widget(DatePicker::class, [
              'options' => [
                'placeholder' => 'Select Date',
                'id'=>'site-date-from',
                'class' => 'form-control'
              ],
              'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
              ]
          ])->label(false); ?>
    </div>

    <div class="col-lg-6 col-md-6 colsm-6 col-6">
      <!-- <label for="labourer-d-o-b">Date of Birth</label> -->
      <?= $form->field($modelMemo, 'date_to')->widget(DatePicker::class, [
              'options' => ['placeholder' => 'To....'],
              'class' => 'form-control',
              'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
              ]
          ],
        ['class' => 'form-control', 'id' => 'date_to','placeholder' => 'To...'])->label(false); ?>
    </div>
    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
      <?=Html::submitButton('Filter', ['class' => 'btn btn-primary']);?>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div>

    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                          'id' =>'pjax-memo-list', 'options'=>['data-loader'=>'.preloader']]); ?>

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
                return Html::a($model->name,['memos/update?id='.$model->id],['data-pjax'=>0]);
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
                $modelIncidentType = $model->getIncidentType($model->incident_id);
                if($modelIncidentType)
                  return $modelIncidentType->name;
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
                  deleteItem('$url','#pjax-memo-list',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],

    ]); ?>
    <?php Pjax::end(); ?>
</div>
