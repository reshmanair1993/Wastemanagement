<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
// use yii\grid\GridView;
use kartik\grid\GridView;

$scrollingTop = 30;
?>
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-technician-list', 'options'=>['data-loader'=>'.preloader']]); ?>

    <p>
<div class="row">
  <div class="col-lg-10 col-md-10 col-sm-6 col-12">
    <h1>Technician</h1>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-12">
  <p>
      <?= Html::a(Yii::t('app', 'Create '), ['create-technician'], ['class' => 'btn btn-success']) ?>
  </p>
  </div>
</div>

<div class="row">
  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      // 'layout' => '{items}',
      'columns' => [

        [
          'label' =>'Name',
          'format' => 'raw',
          'contentOptions'=>[ 'style'=>'width: 250px'],
          'format' => 'raw',
          'value'=>function ($model) {
            $page = isset($_GET['page']) ? $_GET['page']:1;
            return Html::a($model->getName($model->person_id),['monitoring-group-users/update-technician?id='.$model->id],['data-pjax'=>0]);
          },
        ],
        [
          'label' =>'Email',
          'format' => 'raw',
          'value'=>   function ($model) {
            // print_r($model->person_id);exit;
            $modelPerson = $model->getCameraTechnician($model->person_id);
            return $modelPerson;
          },
        ],
        [
          'label' =>'Lsgi',
          'format' => 'raw',
          'value'=>   function ($model) {
            // print_r($model->person_id);exit;
            $modelLsgi = $model->getLsgis($model->lsgi_id);
            return $modelLsgi;
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
            $url =  Yii::$app->urlManager->createUrl(['monitoring-group-users/delete-camera-technician','id'=>$model->id]);

            return  "<a  onclick=\"ConfirmDelete(function(){
              deleteItem('$url','#pjax-technician-list',$page,function() {
              });

            })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

          },
        ],
      ],
      'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        // 'beforeHeader'=>[
        //     [
        //         'columns'=>[
        //             ['content'=>'Header Before 1', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
        //             ['content'=>'Header Before 2', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
        //             ['content'=>'Header Before 3', 'options'=>['colspan'=>3, 'class'=>'text-center warning']],
        //         ],
        //         'options'=>['class'=>'skip-export'] // remove this row from export
        //     ]
        // ],
        'toolbar' =>  [
            [
            // 'content'=>
            //     Html::button('&lt;i class="glyphicon glyphicon-plus">&lt;/i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
            //     Html::a('&lt;i class="glyphicon glyphicon-repeat">&lt;/i>', ['grid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
            ],
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
