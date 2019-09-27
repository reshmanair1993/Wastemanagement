<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
foreach($params as $param => $val)
  ${$param} = $val;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Green Technician</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Green Technician', 'url' => ['account/green-technicians']];
if($modelAccount->id){
   $this->title =  $modelAccount->username;
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
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Update Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Wards</a></li>
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-green-technician-edit','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update-green-technician','id'=>$modelAccount->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>

          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'username')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
            <?php if(!$modelAccount->id){?>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelAccount, 'password_hash')->passwordInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Password');?>
                </div>
              </div>
            </div>
             <?php }?>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Email');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'first_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('First Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'middle_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Middle Name');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'last_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Last Name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone1')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 1');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelPerson, 'phone2')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Phone 2');?>
                </div>
              </div>
            </div>
          </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                    $genders= $modelPerson->getGender();
                    $listData=ArrayHelper::map($genders, 'id', 'name');
                    echo $form->field($modelPerson, 'fk_gender')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Gender')?>
                </div>
              </div>
           </div>
           <?php if($userRole=='super-admin'):?>
           <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $modelAccount->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($modelAccount, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$modelAccount->getDistrict($modelAccount->lsgi_id)])->label('District')?>
                  </div>
                </div>
              </div>
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelAccount, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getConstituency($modelAccount->lsgi_id)],
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

                        echo $form->field($modelAccount, 'block_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getBlock($modelAccount->lsgi_id)],
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

            echo $form->field($modelAccount, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getLsgis($modelAccount->lsgi_id)],
                 'options'=>['id'=>'lsgi-id','class'=>'form-control form-control-line'],
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
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'green_action_unit_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getUnit($modelAccount->green_action_unit_id)],
                'options'=>['id'=>'unit-id','class'=>'form-control form-control-line'],
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
        </div>
         <?php endif;?>
      <?php if($userRole=='admin-lsgi'):?><?php

            echo $form->field($modelAccount, 'lsgi_id')->hiddenInput(['value'=>$modelUser->lsgi_id])->label(false);
            ?>
           <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $unit= $modelAccount->getUnits($modelUser->lsgi_id);
                    $listData=ArrayHelper::map($unit, 'id', 'name');
                    echo $form->field($modelAccount, 'green_action_unit_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'unit-id'])->label('Haritha Karma Sena')?>
                  </div>
                </div>
              </div>
          <?php endif;?>
          <?php if($userRole=='admin-lsgi'||$userRole=='super-admin'):?>
         <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'supervisor_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getSupervisor($modelAccount->supervisor_id)],
                'options'=>['id'=>'supervisor-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['unit-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/account/supervisor'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
          <?php endif;?>
          <?php if($userRole=='admin-hks'):?><?php

            echo $form->field($modelAccount, 'green_action_unit_id')->hiddenInput(['value'=>$modelUser->green_action_unit_id])->label(false);
            ?>
           <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $supervisor= $modelAccount->getSupervisors($modelUser->green_action_unit_id);
                    $listData=ArrayHelper::map($supervisor, 'id', 'first_name');
                    echo $form->field($modelAccount, 'supervisor_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-id'])->label('Supervisor')?>
                  </div>
                </div>
              </div>
          <?php endif;?>
          <?php if($userRole=='supervisor'):?><?php

            echo $form->field($modelAccount, 'green_action_unit_id')->hiddenInput(['value'=>$modelUser->green_action_unit_id])->label(false);
            echo $form->field($modelAccount, 'supervisor_id')->hiddenInput(['value'=>$modelUser->id])->label(false);
            ?>
          <?php endif;?>
        <div class="row">
         
          <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
              <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
          </div>
          </div>
          </div>
        </div>
                  <?php  ActiveForm::end();
                    Pjax::end();
                ?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('../ward', [
                        'modelAccount'=> $modelAccount,
                        'modelPerson'=> $modelPerson,
                        'modelAccountWard' => $modelAccountWard,
                        'dataProvider'=> $dataProvider,
                        ]);?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
