<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Account;
use backend\models\Schedule;
use backend\models\GreenActionUnit;
use backend\models\ResidenceCategory;
use backend\models\Customer;
use backend\models\Service;

$modelAccount = new Account;
$modelSchedule = new Schedule;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Complaint');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Add Complaint</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Complaint';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'add-complaint','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <?php if($userRole=='super-admin'):?>

      <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Haritha karma sena</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','id'=>'unit-id','prompt' => 'Green Action Unit..','name'=>'unit'];
      $key =  isset($_POST['unit'])?$_POST['unit']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $unit=$modelAccount->getGreenActionUnit();
            $listData=ArrayHelper::map($unit, 'id', 'name');

            echo $form->field($modelAccount, 'green_action_unit_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>      
       <?php endif;?>

        <?php if($userRole=='admin-lsgi'):?>

      <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Haritha karma sena</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','id'=>'unit-id','prompt' => 'Green Action Unit..','name'=>'unit'];
      $key =  isset($_POST['unit'])?$_POST['unit']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $unit=$modelAccount->getGreenActionUnit($modelUser->lsgi_id);
            $listData=ArrayHelper::map($unit, 'id', 'name');

            echo $form->field($modelAccount, 'green_action_unit_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>      
       <?php endif;?>
             <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Ward</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','id'=>'ward-id','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      if($userRole=='admin-lsgi'||$userRole=='super-admin'):
      $unit_id =  isset($_POST['unit'])?$_POST['unit']:'';
    else:
      $unit_id = $modelUser->green_action_unit_id;
    endif;
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
           $wards= $modelSchedule->getWardHks($unit_id);
            $listData=ArrayHelper::map($wards, 'id', 'name');

            echo $form->field($modelServiceRequest, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
           <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Residential Accociation</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Residential association..','name'=>'association'];
      $key =  isset($_POST['association'])?$_POST['association']:''; 
       $ward_id =  isset($_POST['ward'])?$_POST['ward']:'';
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected','class'=>'association']];
      }
            $association=$modelAccount->getResidenceAssociations($ward_id);
            $listData=ArrayHelper::map($association, 'id', 'name');

            echo $form->field($modelAccount, 'residence_association')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
       
      
       <?php if($userRole!='supervisor'):?>
 <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
 <label>Supervisor</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Supervisor..','name'=>'supervisor'];
      $sup =  isset($_POST['supervisor'])?$_POST['supervisor']:''; 
       $unit_id =  isset($_POST['unit'])?$_POST['unit']:'';
      if(isset($sup)) { 
        $option = $sup;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $supervisor=$modelAccount->getSupervisors($unit_id);
            $listData=ArrayHelper::map($supervisor, 'id', 'first_name');

            echo $form->field($modelAccount, 'supervisor_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
         <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                $list = $modelServiceRequest->getComplaintsList();
                 $listData=ArrayHelper::map($list, 'id', 'name');
                echo $form->field($modelServiceRequest, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-id','value'=>'','name'=>'service'])->label('Complaint');
                  ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?=$form->field($modelServiceRequest, 'new_complaint')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','name'=>'new_complaint']);?>
              </div>
            </div>
          </div>
          
        </div>
         <div class="row"><?=$form->field($modelServiceRequest, 'customer_id')->hiddenInput(['id'=>'list'])->label(false);?></div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::button('Save',['class' => 'btn btn-success confirm_button','data-status' => 2])?>
    </p>
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
                      'id' =>'pjax-account-gt-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'options' => [
            'class' => 'assign_grid grid-view',
        ],
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns' => [
         [

      'class' => 'yii\grid\CheckboxColumn',
      'headerOptions' => ['class' => 'column-title'],
      'header' => Html::checkBox('selection_column', false, [
      'id' =>'check-all','class'=>'flat'

      ]),
      'contentOptions' => ['class' => 'a-center'],
      //'checkboxOptions' => [],
      'checkboxOptions' => function($model) {
                     return ['data-id' => $model['account_id'],
           'class'=>'ip-chk'];
              },
    ],
               [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return ucwords($model['lead_person_name']);
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                  // [
                  //   'attribute' => 'customer_id',
                  //   'label' => 'Customer Id'
                  // ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Id',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Customer::getFormattedCustomerId($model['customer_id']);
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                  [
                    'attribute' => 'building_type_name',
                    'label' =>'Building Type'
                  ],
                  [
                    'attribute' => 'building_number',
                    'label' =>'Building Number'
                  ],
                  [
                    'attribute' => 'address',
                    'label' =>'Address'
                  ],
                  // [
                  //   'attribute' => 'association_name',
                  //   'label' =>'Association Name'
                  // ],
                  [
                    'attribute' => 'residential_association_id',
                    'label' =>'Association Name',
                    'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    return isset($model['residential_association_id'])?Customer::getResidentailAssociation($model['residential_association_id']):null;
                 },
                  ],
                  [
                    'attribute' => 'association_number',
                    'label' => 'Association Number'
                  ],
                  
        ],
    ]); ?>
     <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });

 $('#check-all').click(function(){
    $('.ip-chk').not(this).prop('checked', this.checked);
});
 ",View::POS_END);
    $this->registerJs('
 $(".confirm_button").click(function() {
  console.log("ok");
   //ok
  var url = "' . Url::to(['requests/add-complaint']) . '";
  var result = [];
   $(".ip-chk:checked").each(function(key,value) {

    result[key] = $(this).attr("data-id");
});
    $("#list").val(result);
     $(".search-form").submit();
 });
 
');
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
</div>