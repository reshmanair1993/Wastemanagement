<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\grid\GridView;
use kartik\depdrop\DepDrop;

$scrollingTop = 30;
$this->title = Yii::t('app', 'Assign Payment Counter Admin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Counter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="assign-payment-counter-acount">
<div class="row bg-title">
  <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
      <h1><?= Html::encode($this->title) ?></h1>
  </div>
  <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Payment Counter', 'url' => ['/payment-counter/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Counter Admin'), 'url' => ['index']];
if($model->id){
   $this->title =  'Create Payment Counter Admin';
}
else
{
   $this->title =  'Create';
}
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
</div>
</div>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,'id' =>'pjax-monitor-user', 'options'=>['data-loader'=>'.preloader']]);?>

<?php $form = ActiveForm::begin(['action' =>['assign-payment-counter-admin','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>

  <div class="col-lg-6 col-md-6 col-sm-6 col-12">
    <div class="row">
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
        <label for="eh-first-name">Counter</label>
        <?php $counterList = ArrayHelper::map($modelCounter,'id','name'); ?>
        <?php
          echo $form->field($model, 'payment_counter_id')->widget(Select2::classname(), [
          'data' => $counterList,
          'language' => 'de',
          'options' => [
            'placeholder' => 'Select Counter',
            'id' => 'payment_counter_id'
          ],
          'pluginOptions' => [
            'placeholder'=>'Select...',
            'allowClear' => true
          ],
          ])->label(false);
        ?>
      </div>
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
        <label for="eh-first-name">Counter Admin</label>
        <?php $usersList = ArrayHelper::map($modelAccount,'id','username'); ?>
        <?php
        echo $form->field($model, 'account_id')->widget(DepDrop::classname(), [
        'type'=>DepDrop::TYPE_SELECT2,
        'options'=>['id'=>'constituency-id','class'=>'form-control form-control-line'],
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
           'depends'=>['payment_counter_id'],
           'class'=>'form-control form-control-line',
            'placeholder'=>'Select...',
            'url'=>Url::to(['/payment-counter/assign-counter-admin'])
        ]
     ])->label(false);
        ?>
      </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-12">
      <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
    </div>
  </div>
<?php ActiveForm::end(); ?>

  <?php
  echo GridView::widget([
      'dataProvider' => $dataProvider,
      // 'layout' => '{items}',
      'columns' => [

        [
          'label' =>'Payment Counter Admin',
          'format' => 'raw',
          'value'=>   function ($model) {
            $modelUser = $model->getPaymentCounterAdmin($model->account_id);
            return $modelUser;
          },
        ],
        [
          'label' =>'Payment Counter',
          'format' => 'raw',
          'value'=>   function ($model) {
            $modelPaymentCounter = $model->getPaymentCounter($model->payment_counter_id);
            return $modelPaymentCounter;
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
            $url =  Yii::$app->urlManager->createUrl(['payment-counter/delete-payment-counter-account','id'=>$model->id]);

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
</div>
<?php Pjax::end(); ?>
