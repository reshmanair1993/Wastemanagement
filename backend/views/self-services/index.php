<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Service;
use backend\models\Customer;
use backend\models\Mrc;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Customer Self Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Customer Self Services</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Customer Self Services';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
       <!-- <?php if(Yii::$app->user->can('service-requests-create')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <p>
          <?= Html::a(Yii::t('app', 'Create Service Request'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
      </div>
    <?php endif;?> -->
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
        <?php if($userRole!='customer'):?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        
           <?php  
           // $keyword =  Yii::$app->session->get('name'); 
           $keyword =  isset($_POST['name'])?$_POST['name']:''; 
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
            

      </div>
    <?php endif;?>
              
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
            $service=Service::getServiceList();
            $listData=ArrayHelper::map($service, 'id', 'name');

            echo $form->field($modelCustomerSelfService, 'service_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Mrc..','name'=>'mrc'];
      $key =  isset($_POST['mrc'])?$_POST['mrc']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $mrc=Mrc::find()->where(['status'=>1])->all();
            $listData=ArrayHelper::map($mrc, 'id', 'name');

            echo $form->field($modelCustomerSelfService, 'mrc_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          
            <div class="col-lg-3 col-sm-6 col-md-8 col-xs-12">
            <input type="text" name="from" value="<?php if (isset($_POST['from'])) echo $_POST['from']; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker"> <a href="" class="active"></a>
      </div>
      <div class="col-lg-3 col-sm-6 col-md-8 col-xs-12">
            <input type="text" name="to" value="<?php if (isset($_POST['to'])) echo $_POST['to']; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker"> <a href="" class="active"></a>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-self-service-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
              [
                    'attribute' => 'customer',
                    'label' =>'Customer Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      // return ucwords($model->getCustomer($model->account_id));
                      return Html::a($model->getCustomer($model->account_id),['self-services/update-self-service?id='.$model->id],['data-pjax'=>0]);
                    },
                  ],
                   [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Id',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->id)?Customer::getFormattedCustomerId($model->fkAccount->fkCustomer->id):null;
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                  [
                    'attribute' => 'qty',
                    'label' => 'Quantity'
                  ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Association Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->fkAssociation)?$model->fkAccount->fkCustomer->fkAssociation->name:null;
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                  [
                    'attribute' => 'fkService.name',
                    'label' => 'Service'
                  ],
                  [
                    'attribute' => 'fkMrc.name',
                    'label' => 'Mrc'
                  ],
                  [
                    'attribute' => 'date',
                    'label' =>'Requested Date',
                    'contentOptions'=>[ 'style'=>'width: 150px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::encode(date('d-M-Y', strtotime($model->created_at)));
                    },
                  ],
                  ];
                   
         if(Yii::$app->user->can('service-requests-delete-request')||$userRole=='super-admin'){
        $columns2 = [             
                   [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['self-services/delete-self-service','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-self-service-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
              ];
    }else
    {
      $columns2 = [];
    }
    $columns = array_merge($columns,$columns2);

?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
    ]); ?>
     <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('.datepicker').on('change', function() {
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
