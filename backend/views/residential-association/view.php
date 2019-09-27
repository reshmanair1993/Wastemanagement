<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Image;
use backend\models\Ward;
$modelImage = new Image;

foreach($params as $param => $val)
  ${$param} = $val;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Residential Association</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Residential Association', 'url' => ['/residential-association']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
if($modelResidentialAssociation->id){
   $this->title =  ucfirst($modelResidentialAssociation->name);
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
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Stakeholders</a></li>
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-association','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update','id'=>$modelResidentialAssociation->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
     <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'association_type_id')->dropDownList($associationTypeList,['maxlength' => true,'class'=>'form-control form-control-line','prompt'=>'Select'])->label('Association Type');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Association name');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'phone1')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'phone2')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <?php if($userRole!='residence-association-admin' &&!isset($associations['lsgi_id'])&&!isset($associations['ward_id'])): ?>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'district_id')->dropDownList($districtList, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'ass-district-id','value'=>$modelResidentialAssociation->getDistrict($modelResidentialAssociation->ward_id)])->label('District')?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                  echo $form->field($modelResidentialAssociation, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'data'=>[$modelResidentialAssociation->getConstituency($modelResidentialAssociation->ward_id)],
                    'options'=>['id'=>'ass-constituency-id','class'=>'form-control form-control-line'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                      'depends'=>['ass-district-id'],
                      'class'=>'form-control form-control-line',
                      'placeholder'=>'Select...',
                      'url'=>Url::to(['/assembly-constituency/constituency'])
                    ]
                    ])->label('Assembly Constituency');
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
                   echo $form->field($modelResidentialAssociation, 'block_id')->widget(DepDrop::classname(), [
                     'type'=>DepDrop::TYPE_SELECT2,
                      'data'=>[$modelResidentialAssociation->getBlock($modelResidentialAssociation->ward_id)],
                     'options'=>['id'=>'ass-block-id','class'=>'form-control form-control-line'],
                     'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                     'pluginOptions'=>[
                       'depends'=>['ass-constituency-id'],
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
                  echo $form->field($modelResidentialAssociation, 'lsgi_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                  'data'=>[$modelResidentialAssociation->getLsgis($modelResidentialAssociation->ward_id)],
                    'options'=>['id'=>'ass-lsgi-id','class'=>'form-control form-control-line'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                      'depends'=>['ass-block-id'],
                      'placeholder'=>'Select...',
                      'class'=>'form-control form-control-line',
                      'url'=>Url::to(['/lsgis/lsgi'])
                    ]
                    ])->label('Lsgi');
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
                    echo $form->field($modelResidentialAssociation, 'ward_id')->widget(DepDrop::classname(), [
                      'type'=>DepDrop::TYPE_SELECT2,
                       'data'=>[$modelResidentialAssociation->getWard($modelResidentialAssociation->ward_id)],
                      'options'=>['id'=>'ass-ward-id','class'=>'form-control form-control-line'],
                      'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                      'pluginOptions'=>[
                        'depends'=>['ass-lsgi-id'],
                        'placeholder'=>'Select...',
                        'class'=>'form-control form-control-line',
                        'url'=>Url::to(['/wards/get-wards'])
                      ]
                      ])->label('Ward');
                      ?>
                  </div>
                </div>
              </div>
            <?php else: 
            ?>
              <?=$form->field($modelResidentialAssociation, 'lsgi_id')->hiddenInput(['value'=>$associations['lsgi_id'],'id'=>'assn-lsgi-id'])->label(false);?>
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                  <div class="col-sm-6 col-xs-6">
                    <?php
                    $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');
                    echo $form->field($modelResidentialAssociation, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'ass-ward-id'])->label('Ward')?>
                  </div>
                </div>
              </div>
            <?php endif;?>
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                  <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelResidentialAssociation, 'registration_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Registration number');?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-6">
                  <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?=$form->field($modelResidentialAssociation, 'address')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Address');?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-xs-6">
                  <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?=$form->field($modelResidentialAssociation, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Email id of association');?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-sm-6 col-xs-6">
                    <div class="form-group">
                      <div class="col-sm-6 col-xs-6">
                        <?=$form->field($modelResidentialAssociation, 'year')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Year of formation');?>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-6">
                    <div class="form-group">
                      <div class="col-sm-6 col-xs-6">
                        <?=$form->field($modelResidentialAssociation, 'president_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('President Name');?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                      <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                          <?=$form->field($modelResidentialAssociation, 'president_phone_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('President Contact Number');?>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                      <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                          <?=$form->field($modelResidentialAssociation, 'secretary_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Secretary Name');?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                          <div class="col-sm-6 col-xs-6">
                            <?=$form->field($modelResidentialAssociation, 'secretary_phone_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Secretary Contact Number');?>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                          <div class="col-sm-6 col-xs-6">
                            <?=$form->field($modelResidentialAssociation, 'treasurer_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Treasurer Name');?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                              <?=$form->field($modelResidentialAssociation, 'treasurer_phone_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Treasurer Contact Number');?>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                              <?=$form->field($modelResidentialAssociation, 'no_of_households_in_association')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('No of households in association');?>
                            </div>
                          </div>
                        </div>
                      </div>
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']);?>
        </div>
        
                  <?php  ActiveForm::end();
                    Pjax::end();
                ?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('stakeholders/add-stakeholder', [
                        'modelResidentialAssociation'=> $modelResidentialAssociation,
                        'modelResidentialAssociationStakeholders' => $modelResidentialAssociationStakeholders,
        'dataProvider' => $dataProvider,
                        ]);?>
                </div>
                
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
