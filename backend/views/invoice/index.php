<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use backend\models\Ward;
use backend\models\District;
use backend\models\Customer;
use backend\models\Account;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$modelCustomer = new Customer;
$this->title = Yii::t('app', 'Payment Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Invoice</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Invoices';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>

          <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
          <div class="form-group" style=" margin-top: -13px;">
          <?php $from = isset($_POST['from'])?$_POST['from']:'';?>
            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
      <div class="form-group" style=" margin-top: -13px;">
      <?php $to = isset($_POST['to'])?$_POST['to']:'';?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
       <?php  if(isset($associations['ward_id'])&&json_decode($associations['ward_id'])&&sizeof(json_decode($associations['ward_id']))>0):
          
            ?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
        <?php endif;?>
      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
          <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-payment-requests-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>"",
        'columns' => [
                    [
                    'attribute' => 'name',
                    'label' =>'Invoice',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      return Html::a('View Invoice',['invoice/generate?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],     
                    [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Id',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->id)?Customer::getFormattedCustomerId($model->fkAccount->fkCustomer->id):'';
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                    'amount',
                    [
                    'attribute' =>  'date',
                     'label' =>'Date',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return date('d-M-Y', strtotime($model->requested_date));
                    },
                  ],
                  [
                    'attribute' => 'name',
                    'label' =>'Download',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      return Html::a('Download Pdf',['invoice/download-pdf?id='.$model->id],['data-pjax'=>0]);
                    },
                  ], 
        ],
    ]); ?>
 <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });
 ",View::POS_END);
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
