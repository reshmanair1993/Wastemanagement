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
use backend\models\BuildingType;
use backend\models\ResidentialAssociation;
 use kartik\select2\Select2;
 use yii\helpers\Url;
 $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SbhrmAssetAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $newDataProvider = $dataProvider;
// $newDataProvider->pagination = false;
$scrollingTop = 30;
$this->title = Yii::t('app', 'Public Customer List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sbhrm-asset-allocation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Public Customer List</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Public Customer List';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>

      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['customers/public-customer-list','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        <label>Name</label>
           <?php
           // $keyword =  Yii::$app->session->get('name');
           $keyword =  isset($_POST['name'])?$_POST['name']:'';
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>


      </div>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        <label>Customer Id</label>
           <?php
           // $keyword =  Yii::$app->session->get('name');
           $id =  isset($_POST['customer_id'])?$_POST['customer_id']:'';
           ?>
            <input type="text" name="customer_id" value="<?php if (isset($id)) echo $id; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Customer id..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>


      </div>
       <?php if(!isset($associations['district_id'])):?>
       <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'District..','name'=>'district'];
      $key =  isset($_POST['district'])?$_POST['district']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $district=Ward::getDistricts();
            $listData=ArrayHelper::map($district, 'id', 'name');

            echo $form->field($modelCustomer, 'district_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(!isset($associations['lsgi_id'])):?>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Lsgi..','name'=>'lsgi'];
      $key =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $lsgi=Account::getLsgi();
            $listData=ArrayHelper::map($lsgi, 'id', 'name');

            echo $form->field($modelCustomer, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <?php endif;?>
           <?php  if(!isset($associations['ward_id'])):
          
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
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
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
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
        <?php endif;?>
         
           <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">

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
            // $listData = array_merge($listData, $listDataNew); 
            $listData=ArrayHelper::map($list, 'id', 'name');
            echo $form->field($modelCustomer, 'residential_association_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>

           <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
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
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
   <br>
   <div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-customers-list', 'options'=>['data-loader'=>'.preloader']]); ?>
   <?php
   $columns = [];
   $gridSettings = [
     "Customers-show-name" => [
       'attribute' =>  'lead_person_name',
       'label' =>'Name',
       'contentOptions'=>[ 'style'=>'width: 250px'],
       'format' => 'raw',
       'value'=>function ($model) {
         $page = isset($_GET['page']) ? $_GET['page']:1;
         // return Html::a(Html::encode(ucwords($model->lead_person_name)),['customers/view?id='.$model->id],['data-pjax'=>0]);
          return Html::encode(ucwords($model->lead_person_name));

       },
     ],
     "Customers-show-ward" => 'fkWard.name_en',
     "Customers-show-date" => [
       'attribute' =>  'date',
        'label' =>'Date',
       'contentOptions'=>[ 'style'=>'width: 250px'],
       'format' => 'raw',
       'value'=>function ($model) {
         $page = isset($_GET['page']) ? $_GET['page']:1;
         return date('Y-m-d', strtotime($model->created_at));
       },
     ],
     "Customers-show-time" => [
       'attribute' =>  'time',
       'label' =>'Time',
       'contentOptions'=>[ 'style'=>'width: 250px'],
       'format' => 'raw',
       'value'=>function ($model) {
         $page = isset($_GET['page']) ? $_GET['page']:1;
         return date('h:i:s a', strtotime($model->created_at));
       },
     ],
    
     "Customers-show-association-name" => [
       'attribute' =>  'residential_aociation',
       'label' =>'Association Name',
       'contentOptions'=>[ 'style'=>'width: 250px'],
       'format' => 'raw',
       'value'=>function ($model) {
         $page = isset($_GET['page']) ? $_GET['page']:1;
         return $model->fkAssociation?$model->fkAssociation->name:null;
       },
     ],

     "Customers-show-association-number" => 'association_number',
     "Customers-show-lead-person-phone" => 'lead_person_phone',
     "Customers-show-delete" => [
       'attribute' => 'delete',
       'label' =>'Delete',
       'contentOptions'=>[ 'style'=>'width: 50px'],
       'format' => 'raw',
       'value'=>function ($model) {
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $page = $page;
        $url =  Yii::$app->urlManager->createUrl(['customers/delete-customer','id'=>$model->id]);

        return  "<a  onclick=\"ConfirmDelete(function(){
          deleteItem('$url','#pjax-customers-list',$page,function() {
          });

        })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";
      },
     ],
   ];
   foreach($gridSettings as $permission => $column) {
       if(Yii::$app->user->can($permission)) {
          $columns[] = $column;
       }
   }
  if(Yii::$app->user->can('Customers-view-export')||$userRole=='super-admin'){
   echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
    'toolbar' =>  [
        [
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
}else
{
   echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
    'toolbar' =>  [
        [
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
]);
  }?>
</div>
 <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('#w1-togdata-page').on('click', function() {
 window.location.reload();

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
