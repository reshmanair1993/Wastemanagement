<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;

foreach($params as $param => $val)
  ${$param} = $val;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
 $earned = $model->performance_point_earned?$model->performance_point_earned:0;
    $total = $model->performance_point_total?$model->performance_point_total:0;
 if($earned!=0){
      $rating = $earned/$total*100;
  
    }
    else
    {
        $rating = 0;
    }
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Haritha Karma Sena</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Haritha Karma Sena', 'url' => ['/green-action-units']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Haritha Karma Sena'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->name);
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
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Haritha Karma Sena Info</a></li>
             <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Services</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Wards</a></li>
           
            <!-- <li><a href="#tab_4" data-toggle="tab" aria-expanded="false">Schedule</a></li> -->
        </ul>
        <div class="tab-content" style="margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
            <div class="row">
            <table style="margin-left: 128px;font-size: 30px;">
            <tr>
            <td >HARITHAKARMASENA RATING</td>
            <td style="padding-left: 75px;"><?=round($rating,2)?>/100</td>
            </tr>
            </table>
            </div>
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-green-action-unit','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'contact_person_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'address')->textArea(['class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <?php if($userRole!='admin-lsgi'):?>
         <div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
        <?php $district= $model->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->lsgi_id)])->label('District')?>
                  </div>
            </div>
        </div>
    </div>
    <div class="row">
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
    </div>
    <div class="row">
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$model->getLsgis($model->lsgi_id)],
                'options'=>['class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['block-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/lsgis/lsgi'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
            <?php else:?>
            <?php

            echo $form->field($model, 'lsgi_id')->hiddenInput(['value'=>$modelUser->lsgi_id])->label(false);
            ?>
          <?php endif;?>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $category= $model->getCategory();
                    $listData=ArrayHelper::map($category, 'id', 'name');
                    echo $form->field($model, 'residence_category_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'category-id'])->label('Residence Category')?>
                  </div>
                </div>
              </div>
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                    <?php
                    echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
                    ActiveForm::end();
                    Pjax::end();
                ?>
            </div>
            </div>
            </div>
            </div>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('ward', [
                        'model'=> $model,
                        'modelGreenActionUnitWard'=> $modelGreenActionUnitWard,
                        'searchModel'=> $searchModel,
                        'dataProvider'=> $dataProvider,
                        ]);?>
                </div>
                <div class="tab-pane" id="tab_3">
                    <?=$this->render('service/service', [
                        'model'=> $model,
                        'modelGreenActionUnitService'=> $modelGreenActionUnitService,
                        // 'searchModel'=> $searchModel,
                        'serviceDataProvider'=> $serviceDataProvider,
                        ]);?>
                </div>
                
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
