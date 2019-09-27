<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;

$scrollingTop = 30;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Controllers';
$this->params['breadcrumbs'][] = $this->title;

foreach($params as $param => $val)
  ${$param} = $val;

?>
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-controllers-list', 'options'=>['data-loader'=>'.preloader']]); ?>

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
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
             <p>
        <?= Html::a(Yii::t('app', 'Create Access Controllers'), ['create-controllers'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
  </div>
  <div class="col-lg-12 col-md-12 colsm-6 col-6">

</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
          'label' =>'Controllers name',
          'contentOptions'=>[ 'style'=>'width: 250px'],
          'format' => 'raw',
          'value'=>function ($modelAuthControllers) {
            return Html::a(ucfirst($modelAuthControllers->name),['rbac/update-controllers','id'=>$modelAuthControllers->id],['data-pjax'=>0]);
          },
        ],
        [
           'label' =>'Delete',
           'contentOptions'=>[ 'style'=>'width: 50px'],
           'format' => 'raw',
           'value'=>function ($modelAuthControllers) {
            $page = isset($_GET['page']) ? $_GET['page']:1;
            $page = $page;
            $url =  Yii::$app->urlManager->createUrl(['rbac/delete-controllers','id'=>$modelAuthControllers->id]);

            return  "<a  onclick=\"ConfirmDelete(function(){
              deleteItem('$url','#pjax-controllers-list',$page,function() {
              });

            })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

          },
        ],
    ],

    'containerOptions' => ['style'=>'overflow: auto'],
      'toolbar' =>  [
          '{export}',
          '{toggleData}'
      ],
      'pjax' => true,
      'bordered' => true,
      'striped' => false,
      'condensed' => false,
      'responsive' => true,
      'hover' => true,
      'floatHeader' => true,
      'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
      'showPageSummary' => true,
      'panel' => [
          'type' => GridView::TYPE_PRIMARY
      ],
  ]);
?>
</div>
<?php Pjax::end(); ?>
