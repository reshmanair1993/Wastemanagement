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

$this->title = 'Installation Checklist';
$this->params['breadcrumbs'][] = $this->title;

foreach($params as $param => $val)
  ${$param} = $val;

?>
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-camera-installation-list', 'options'=>['data-loader'=>'.preloader']]); ?>

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
        <?= Html::a(Yii::t('app', 'Create Installation Checklist'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
  </div>
  <div class="col-lg-12 col-md-12 colsm-6 col-6">

</div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout' => '{items}',
        'columns' => [

            [
              'label' =>'Name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelCameraInstallationCheckList) {
                return Html::a(ucfirst($modelCameraInstallationCheckList->name),['installation-checklist/update','id'=>$modelCameraInstallationCheckList->id],['data-pjax'=>0]);
              },
            ],
            [
              'label' =>'Image',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelCameraInstallationCheckList) {
                $imageId = $modelCameraInstallationCheckList->image_id;
                if($imageId){
                  return "<img src =".$modelCameraInstallationCheckList->getImage($imageId)." class = camera-img-holder >";
                }
                else {
                  return 'Nil';
                }
              },
            ],
            [
               'label' =>'Delete',
               'contentOptions'=>[ 'style'=>'width: 50px'],
               'format' => 'raw',
               'value'=>function ($modelCameraInstallationCheckList) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $page = $page;
                $url =  Yii::$app->urlManager->createUrl(['camera-installation/delete-camera-installation','id'=>$modelCameraInstallationCheckList->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-camera-installation-list',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
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
