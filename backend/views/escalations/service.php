<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Service;
use backend\models\Customer;
use backend\models\ServiceRequest;
use backend\models\Account;
use backend\models\Ward;
use yii\helpers\Url;
use backend\models\ResidentialAssociation;
$scrollingTop = 10;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelCustomer = new Customer;
$modelAccount = new Account;
$this->title = Yii::t('app', 'Escalated Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Escalated Services</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Escalated Services';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['escalations/service','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
        <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      // $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      $key =    Yii::$app->session->get('ward');
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <!--  <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">

           <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Association..','name'=>'association'];
      $key =  isset($_POST['association'])?$_POST['association']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $list=ResidentialAssociation::getAssociationslist();
            // $listDataNew= ['-1' => 'No Association'];
            $listData=ArrayHelper::map($list, 'id', 'name');
            // $listData = array_merge($listData, $listDataNew); 
            echo $form->field($modelCustomer, 'residential_association_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div> -->
            <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Service..','name'=>'service'];
      $key =  isset($_POST['service'])?$_POST['service']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $service=Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['is_package'=>0])->all();
            $listData=ArrayHelper::map($service, 'id', 'name');

            echo $form->field($modelServiceRequest, 'service_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <?php if($userRole!='supervisor'):?>
 <!-- <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Supervisor..','name'=>'supervisor'];
      $sup =  isset($_POST['supervisor'])?$_POST['supervisor']:''; 
      
      if(isset($sup)) { 
        $option = $sup;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $supervisor=$modelAccount->getSupervisors();
            $listData=ArrayHelper::map($supervisor, 'id', 'first_name');

            echo $form->field($modelAccount, 'supervisor_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div> -->
          <?php endif;?>
 <!-- <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Green Technician..','name'=>'gt'];
      $sup =  isset($_POST['gt'])?$_POST['gt']:''; 
      
      if(isset($sup)) { 
        $option = $sup;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $gt=$modelAccount->getGt();
            $listData=ArrayHelper::map($gt, 'id', 'first_name');

            echo $form->field($modelAccount, 'gt_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <div class="col-lg-2 col-sm-6 col-md-6 col-xs-12">
          <?php $from = Yii::$app->session->get('from');?>
            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
      <?php $to = Yii::$app->session->get('to');?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div> -->
      <?php ActiveForm::end(); ?>
      </div>
</div>
   <br>
   <div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php 
  // Pjax::begin(['timeout' => 50000,'enablePushState' => false,
  //                     'id' =>'pjax-customers-list', 'options'=>['data-loader'=>'.preloader']]);
                       ?>
   <?php
   $columns = [
   ['class' => 'kartik\grid\SerialColumn'],
            [
                    'attribute' => 'customer',
                    'label' =>'Customer Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      // print_r($model);die();
                        return ucwords(ServiceRequest::getCustomer($model['account_id_customer']));
                    },
                  ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Id',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $model = ServiceRequest::find()->where(['id'=>$model['id']])->one();
                      return isset($model->fkAccount->fkCustomer->id)?$model->fkAccount->fkCustomer->getFormattedCustomerId($model->fkAccount->fkCustomer->id):null;
                    },
                  ],
                  [
                    'attribute' => 'service',
                    'label' =>'Service',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                        return ucwords($model['name']);
                    },
                  ],
                  [
                    'attribute' => 'service',
                    'label' =>'Requested Date',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                        return $model['requested_datetime'];
                    },
                  ],
                  [
                    'attribute' =>  'gt',
                     'label' =>'Green Technician',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $model = ServiceRequest::find()->where(['id'=>$model['id']])->one();
                      return isset($model->fkServiceAssignment->fkAccount->fkPerson)?$model->fkServiceAssignment->fkAccount->fkPerson->first_name:'';
                    },
                  ],
                  [
                    'attribute' =>  'association',
                    'label' =>'Association',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $model = ServiceRequest::find()->where(['id'=>$model['id']])->one();
                      return isset($model->fkAccount->fkCustomer->fkAssociation->name)?$model->fkAccount->fkCustomer->fkAssociation->name:'';
                    },
                  ],
                  // [
                  //   'attribute' => 'status',
                  //   'label' =>'status',
                  //   'contentOptions'=>[ 'style'=>'width: 150px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //     return ServiceRequest::getStatuses($model['id']);
                  //   },
                  // ],
                
        ];
  
   echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => $columns,
    'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Escalated Services-'.date('d-M-Y')],
                   GridView::HTML => ['label' => 'Export as HTML', 'filename' => 'Escalated Services -'.date('d-M-Y')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Escalated Services -'.date('d-M-Y')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Escalated Services -'.date('d-M-Y')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Escalated Services -'.date('d-M-Y')],
                ],
    // 'summary'=>'',
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
    'pjax' => false,
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
]);?>
</div>
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
        $('#w1-togdata-page').on('click', function() {
 location.reload();
 });
 ",View::POS_END);
 // Pjax::end();
 ?>
</div>
</div>
</div>
</div>
</div>
