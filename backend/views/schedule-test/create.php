<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Account;
use backend\models\Customer;
$modelAccount = new Account;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Schedule');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"> Schedule</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Schedule';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     <!--  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::button('Add Gt',['class' => 'btn btn-success confirm_button','data-status' => 2,'data-toggle'=>"modal" ,'data-target'=>"#myModal"])?>
    </p>
        </div> -->
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'create','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Ward</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'ward..','name'=>'ward','id'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $wards= $model->getWardHks($modelUser->green_action_unit_id);
            $listData=ArrayHelper::map($wards, 'id', 'name');

            echo $form->field($model, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
      
           <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
      <label>Green Technician</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'gt..','name'=>'gt'];
      $key =  isset($_POST['gt'])?$_POST['gt']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $gt= $model->getGt($modelUser->green_action_unit_id,$modelUser->id);
            $listData=ArrayHelper::map($gt, 'id', 'first_name');

            echo $form->field($model, 'account_id_gt')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
               <div class="col-lg-6 col-sm-6 col-md-4 col-xs-12">
      <label>Service</label>
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'service..','name'=>'service'];
      $key =  isset($_POST['service'])?$_POST['service']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $services= $model->getServiceList();
            $listData=ArrayHelper::map($services, 'id', 'name');

            echo $form->field($model, 'service_id')->dropDownList($listData, $options)->label(false)?>
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

          <!--  -->

          <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'type')->dropDownList([1=>'Weekly',2=>'Monthly',3=>'Date Wise'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line configuration_type','id'=>'configuration_type','value'=>$model->type])?>
                    </div>
                </div>
            </div>
            <section id="weekly">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'week_day')->dropDownList([1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            </section>
            <section id="mothly">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'month_day')->dropDownList(range(1,31), ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            </section>
            <section id="date-wise">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'date')->textInput(['maxlength' => true,'class'=>'form-control form-control-line datepicker']);?>
                </div>
            </div>
        </div>
        </section>   
         <div class="row"><?=$form->field($model, 'customer_id[]')->hiddenInput(['id'=>'list'])->label(false);?></div>
      <?php ActiveForm::end(); ?>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
             <p>
        <?= Html::button('Save',['class' => 'btn btn-success confirm_button','data-status' => 2])?>
    </p>
        </div>
      </div>
</div>
<div class="row">
<input type="label" id="edit-count-checked-checkboxes" style="margin-left: 15px;" readonly="readonly" />
</div><br>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-account-customer-list', 'options'=>['data-loader'=>'.preloader']]); ?>
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
                 //  [
                 //    'attribute' => 'association_name',
                 //    'label' =>'Association Name',
                 // //    'value'=>function ($model) {
                 // //    // $page = isset($_GET['page']) ? $_GET['page']:1;
                 // //    // return $model->fkAssociation?$model->fkAssociation->name:null;
                 // // },
                 //  ],
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
     $count = 0;
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
    $('#Pjax-word-add').on('pjax:end', function() {
      $.pjax.reload({container:'#pjax-account-customer-list','push':false});
    });
var count =0;
 $('#check-all').click(function(){
    $('.ip-chk').not(this).prop('checked', this.checked);
    $('.ip-chk:checked').each(function(key,value) {
    count = count+1;
});
$('#edit-count-checked-checkboxes').show();
 $('#edit-count-checked-checkboxes').val(count);
});
$('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });
       $(document).ready(function(){
        $('#edit-count-checked-checkboxes').hide();
         // $('#customer').hide();
         var val = $('#configuration_type').val();
      if(val==1)
      {
                $('#weekly').show();
                $('#mothly').hide();
                $('#date-wise').hide();
      }
      if(val==2)
      {
                $('#weekly').hide();
                $('#mothly').show();
                $('#date-wise').hide();
      }
      if(val==3)
      {
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').show();
      }
      if(!val){
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').hide(); 
                }              
            });
    $('#configuration_type').on('change', function() {
      var val = $('#configuration_type').val();
      if(val==1)
      {
                $('#weekly').show();
                $('#mothly').hide();
                $('#date-wise').hide();
      }
      if(val==2)
      {
                $('#weekly').hide();
                $('#mothly').show();
                $('#date-wise').hide();
      }
      if(val==3)
      {
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').show();
      }
    });
 ",View::POS_END);
    $this->registerJs('
 $(".confirm_button").click(function() {
  var result = []; 
   $(".ip-chk:checked").each(function(key,value) {
    result[key] = $(this).attr("data-id");
});
    $("#list").val(result);
    $(".search-form").submit();
 });
 var count =0;
 $(".ip-chk").click(function() {
count = count+1;
$("#edit-count-checked-checkboxes").show();
$("#edit-count-checked-checkboxes").val(count);
  });
 
');
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
