<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
// use yii\grid\GridView;
use kartik\select2\Select2;
use kartik\grid\GridView;

$scrollingTop = 30;


?>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,'id' =>'pjax-monitor-user', 'options'=>['data-loader'=>'.preloader']]);?>

<?php $form = ActiveForm::begin(['action' =>['add-monitoring-group-user','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>

<div class="col-lg-6 col-md-6 col-sm-6 col-12">
  <h1>Users</h1>
  <div class="row">
    <div class="form-group col-lg-10 col-md-10 col-sm-6 col-12">
      <!-- <label for="eh-first-name">Account Technician</label> -->
      <?php $usersList = ArrayHelper::map($modelAccount,'id','username'); ?>
      <?php
        echo $form->field($modelMonitoringGroupUser, 'account_id')->widget(Select2::classname(), [
        'data' => $usersList,
        'language' => 'de',
        'options' => ['placeholder' => 'Select User'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ])->label(false);
      ?>

    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-12">
      <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
    </div>
  </div>
<?php ActiveForm::end(); ?>

  <?= GridView::widget([
      'dataProvider' => $userDataProvider,
      // 'layout' => '{items}',
      'columns' => [

        [
          'label' =>'User Name',
          'format' => 'raw',
          'value'=>   function ($model) {
            $modelUser = $model->getMonitoringGroupUsers($model->account_id);
            return $modelUser;
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
            $url =  Yii::$app->urlManager->createUrl(['monitoring-groups/delete-monitoring-group-user','id'=>$model->id]);

            return  "<a  onclick=\"ConfirmDelete(function(){
              deleteItem('$url','#pjax-monitor-user',$page,function() {
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
