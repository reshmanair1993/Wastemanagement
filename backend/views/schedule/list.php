<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use backend\models\District;
use backend\models\Account;
use backend\models\Customer;
use backend\models\ResidentialAssociation;
use backend\models\Service;
 use kartik\select2\Select2;
 use yii\helpers\Url;
 $modelCustomer = new Customer;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SbhrmAssetAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $newDataProvider = $dataProvider;
// $newDataProvider->pagination = false;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$scrollingTop = 30;
$this->title = Yii::t('app', 'Schedule');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Schedule</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Schedule';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
     <?php $form = ActiveForm::begin(['action' =>Url::to(['schedule/index','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
            <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <label>Ward</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward']; 
      $key =    Yii::$app->session->get('ward');
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($model, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
<label>Association</label>
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
            $listData=ArrayHelper::map($list, 'id', 'name');
            echo $form->field($model, 'residential_association_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
                <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Service</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'service..','name'=>'service'];
      $key =  isset($_POST['service'])?$_POST['service']:''; 
      if($userRole=='admin-lsgi'||$userRole=='super-admin'):
      $unit_id =  isset($_POST['unit'])?$_POST['unit']:'';
    else:
      $unit_id = $modelUser->green_action_unit_id;
    endif;
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            // $services= $model->getServiceList();
      $services=Service::find()
            ->where(['service.status'=>1])
            ->andWhere(['service.type'=>1])
            ->andWhere(['not',['service.is_package'=>1]])
            ->all();
            $listData=ArrayHelper::map($services, 'id', 'name');

            echo $form->field($model, 'service_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
          <?php $from = isset($_POST['from'])?$_POST['from']:'';?>
            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
      <?php $to = isset($_POST['to'])?$_POST['to']:'';?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
       <p>
        <?= Html::a(Yii::t('app', 'Create Schedule'), ['create-non-residential-schedule'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
   <br>
   <div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-schedule-list', 'options'=>['data-loader'=>'.preloader']]); ?>
   <?php
   $columns = [
            [
                    'attribute' =>  'type',
                    'label' =>'Type',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('schedule-update')||$userRole=='super-admin'):
                      return Html::a($model->fkType->name,['schedule/view-non-residential-schedule?id='.$model->id],['data-pjax'=>0]);
                     else:
                        return $model->fkType->name;
                      endif;
                    },
                  ],
                  [
                    'attribute' =>  'week_day',
                    'label' =>'Week Day',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getWeekDay();
                    },
                  ],
                  [
                    'attribute' =>  'month_day',
                    'label' =>'Month Day',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->month_day;
                    },
                  ],
                  [
                    'attribute' =>  'date',
                    'label' =>'Date',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->date?date('d-M-Y',strtotime($model->date)):null;
                    },
                  ],
                  // [
                  //   'attribute' =>  'lsgi_id',
                  //   'label' =>'Lsgi',
                  //   'contentOptions'=>[ 'style'=>'width: 250px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //     return $model->getLsgis($model->lsgi_id);
                  //   },
                  // ],
                  [
                    'attribute' =>  'service',
                    'label' =>'Service',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a($model->getService());
                    },
                  ],
                  [
                    'attribute' =>  'ward_id',
                    'label' =>'Ward',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->fkWard?$model->fkWard->name:null;
                    },
                  ],
                  // [
                  //   'attribute' =>  'residential_association_id',
                  //   'label' =>'Association Name',
                  //   'contentOptions'=>['style'=>'width: 250px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //     return isset($model->fkAssociation)?$model->fkAssociation->name:'--';
                  //   },
                  // ],
                  [
                    'attribute' =>  'ward_id',
                    'label' =>'Customer Count',
                    'contentOptions'=>['style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getCount($model->id);
                    },
                  ],
                  
        ];
        if(Yii::$app->user->can('schedule-delete-schedule')||$userRole=='super-admin'){
        $columns2 = [             
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['schedule/delete-schedule','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-schedule-list',$page,function() {
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
  
   echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => $columns,
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
 ",View::POS_END);
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
