<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Customer;
 $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
    // $value = null;
    // if($model->id)
    // {
    //     if(isset($model->week_day))
    //     {
    //         $value = 1;
    //     }
    //     elseif(isset($model->mothly))
    //     {
    //         $value = 2;
    //     }
    //     elseif(isset($model->date))
    //     {
    //         $value = 3;
    //     }
    // }
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Schedule</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Schedule', 'url' => ['/schedule/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schedule'), 'url' => ['index']];
if(!$model->id){
     $this->title =  'Create';
     }
     else
      {
         $this->title =  'Update';
        }
  
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
    <?php
    if(!$model->id){
     $form = ActiveForm::begin(['action' => ['add-hks-schedule'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
     }
     else
      {
         $form = ActiveForm::begin(['action' => ['update?id='.$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
        }?>
        <?php if($userRole=='super-admin'):?>
         <div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
        <?php $district= $model->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->lsgi_id)])->label('District')?>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($model, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$model->getConstituency($model->lsgi_id)],
                        'options'=>['id'=>'constituency-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['district-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/assembly-constituency/constituency'])
                        ]
                    ])->label('Assembly Constituency');
                    ?>
                    </div>
                </div>
            </div>
          <div class="col-sm-6 col-xs-6">
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($model, 'block_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$model->getBlock($model->lsgi_id)],
                        'options'=>['id'=>'block-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['constituency-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/lsgi-blocks/blocks'])
                        ]
                    ])->label('Block');
                    ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getLsgis($model->lsgi_id)],
                'options'=>['id'=>'lsgi-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['block-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/lsgis/lsgi'])
                ]
            ])->label('Lsgi');
            ?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'green_action_unit_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getUnit($model->green_action_unit_id)],
                'options'=>['id'=>'hks-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/green-action-units/unit'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?php

            echo $form->field($model, 'ward_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getWard($model->ward_id)],
                'options'=>['class'=>'form-control form-control-line ward-id','id'=>'ward-id'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['hks-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/wards/wards'])
                ]
            ])->label('Ward');
            ?>
                </div>
            </div>
        </div>
      <?php endif;?>
        <?php if($userRole=='supervisor'):?><?php
            echo $form->field($model, 'lsgi_id')->hiddenInput(['value'=>$modelUser->lsgi_id])->label(false);
            echo $form->field($model, 'green_action_unit_id')->hiddenInput(['value'=>$modelUser->green_action_unit_id])->label(false);
            ?>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group" style="margin-left:12px;">
                <div class="col-sm-6 col-xs-6">
                    <?php $wards= $model->getWardHks($modelUser->green_action_unit_id);
                    $listData=ArrayHelper::map($wards, 'id', 'name');
                    echo $form->field($model, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line ward-id','id'=>'ward-id'])->label('Ward')?>
                </div>
            </div>
        </div>
          <?php endif;?>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $services= $model->getServices();
                    $listData=ArrayHelper::map($services, 'id', 'name');
                    echo $form->field($model, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line services-id','id'=>'services-id'])->label('Service')?>
                  </div>
                </div>
              </div>
              <?php if($userRole=='super-admin'):?>
              <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?php

            echo $form->field($model, 'account_id_gt')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getGtName($model->account_id_gt)],
                'options'=>['id'=>'gt_select','class'=>'form-control form-control-line gt_select'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['hks-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/account/get-gt'])
                ]
            ])->label('Green Technician');
            ?>
                </div>
            </div>
        </div>
      <?php endif;?>
       <?php if($userRole=='supervisor'):?>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group" style="margin-left:12px;">
                <div class="col-sm-6 col-xs-6">
                    <?php $gt=$model->getGt($modelUser->green_action_unit_id);
                $listData=ArrayHelper::map($gt, 'id', 'first_name'); 
                echo $form->field($model, 'account_id_gt')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line gt_select','id' => 'gt_select'])->label('Green Technician')?>
                </div>
            </div>
        </div>
          <?php endif;?>
         
           <!-- <div class="row customer" id="customer">
      <div class="col-sm-8 col-xs-8">
          <div class="form-group" style="margin-left:12px;">
              <div class="col-sm-8 col-xs-8">
        <?php 
        $list = ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
          ->leftJoin('account_service','account.id=account_service.account_id')
          ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
          ->andWhere(['customer.ward_id'=>$model->ward_id])
          ->andWhere(['account_service.service_id'=>$model->service_id])
          ->andWhere(['account_authority.account_id_gt'=>$model->account_id_gt])
          ->all(), 'id', 'lead_person_name');
        echo $form->field($model, 'customer_id')->checkboxList($list, ['class' => 'form-control customer_select' ,'style'=>'height:100%'])->label('Customers') ?>
        </div>
      </div>
    </div>
    </div> -->
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
         <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
            </div>
            </div>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
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
       $(document).ready(function(){
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
    $('#lsgi-id').on('change',function(){
            var cat = $('#lsgi-id').val();
            if(cat){
                $.ajax({ 
                    url:'ward-ajax',
                    data:{cat:cat},
                    method:'POST',
                    success: function(data) {
                        var target = $('.type_select');
                        target.empty();
                        $.each(data , function(key , value){
                            // var input = '<option value='+key+' >'+value+'</option>';
                          var checkbox='checkbox';
                          method='Schedule[ward_id][]';
                            var input = '<input type='+checkbox+' value='+key+' name='+method+'>'+value+'</key>';
                            target.append(input);
                        })
                    }
                });
            }
        })
  $('.gt_select').on('change',function(){
     $('#customer').show();

            var gt = $('.gt_select').val();
            var ward = $('.ward-id').val();
            var service = $('.services-id').val();
            if(gt){
                $.ajax({ 
                    url:'gt-ajax',
                    data:{gt:gt,ward:ward,service:service},
                    method:'POST',
                    success: function(data) {
                        var target = $('.customer_select');
                        target.empty();
                        $.each(data , function(key , value){
                            // var input = '<option value='+key+' >'+value+'</option>';
                          var checkbox='checkbox';
                          method='Schedule[customer_id][]';
                            var input = '<input type='+checkbox+' value='+key+' name='+method+'>'+value+'</key>';
                            target.append(input);
                        })
                    }
                });
            }
        })
 ",View::POS_END);?>