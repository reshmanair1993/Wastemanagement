<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lsgi Authorized Signatories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lsgi-authorized-signatory-index">

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
    <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                          'id' =>'pjax-lsgi-authorized-signatories', 'options'=>['data-loader'=>'.preloader']]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
                return Html::a($model->name,['lsgi-authorized-signatories/update?id='.$model->id],['data-pjax'=>0]);
              },
            ],
            [
              'label' =>'Position',
              'format' => 'raw',
              'value'=>  'position',
            ],
            [
              'label' =>'Lsgi',
              'format' => 'raw',
              'value'=>   function ($model) {
                $modelLsgi = $model->getLsgi($model->lsgi_id);
                if($modelLsgi)
                  return $modelLsgi->name;
              },
            ],
            [
              'label' =>'Signature Image',
              'format' => 'raw',
              'value'=>  function ($model) {
                $modelSignatureImage = $model->getSignatureImage($model->image_id_signature);
                if($modelSignatureImage)
                  return $this->render('signature-image',['modelSignatureImage' => $modelSignatureImage]);
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
                $url =  Yii::$app->urlManager->createUrl(['lsgi-authorized-signatories/delete-lsgi-authorized-signatory','id'=>$model->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-lsgi-authorized-signatories',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],

    ]);?>
    <?php Pjax::end(); ?>
    <?php
      $title = isset($title)?$title:'Success';
      $type = isset($type)?$type:'success';
      $message = isset($message)?$message:'Authorized Signatory has been added successfully';
      $title = Html::encode(trim($title));
      $message = Html::encode(trim($message));
      $title =  $title;
      $message =  $message; //but need to escape apppstrope
      if ($showSuccess == 1):
        $this->registerJs("
        swal({title:'$title',text: '$message', type:'$type'});
        ");
      endif ;
      if ($updateSuccess == 1):
        $this->registerJs("
        swal({title:'Success',text: 'Authorized Signatory has been updated successfully', type:'$type'});
        ");
      endif ;
    ?>
</div>
