<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;
use backend\models\Ward;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
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
   $breadcrumb[]  = ['label' => 'Residential Association', 'url' => ['residential-association/index']];
if($modelResidentialAssociation->id){
   $this->title =  $modelResidentialAssociation->username;
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
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

        <?php $form = ActiveForm::begin(['action' =>['create']]);?>
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
                  <?=$form->field($modelResidentialAssociation, 'name')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Association name');?>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($modelResidentialAssociation, 'phone1')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line']);?>
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
                  <?=$form->field($modelResidentialAssociation, 'district_id')->dropDownList($districtList, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'ass-district-id'])->label('District')?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php
                  echo $form->field($modelResidentialAssociation, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    // 'data'=>[$model->getConstituency($model->lsgi_id)],
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
                     // 'data'=>[$model->getBlock($model->lsgi_id)],
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
                    // 'data'=>[$model->getLsgis($model->lsgi_id)],
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
                      // 'data'=>[$model->getWard($model->ward_id)],
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
              <?php else:?>
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
                    <?=$form->field($modelResidentialAssociation, 'registration_number')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Registration number');?>
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
                      <?=$form->field($modelResidentialAssociation, 'email')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Email id of association');?>
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
                        <?=$form->field($modelResidentialAssociation, 'president_name')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('President Name');?>
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
                          <?=$form->field($modelResidentialAssociation, 'secretary_name')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Secretary Name');?>
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
                            <?=$form->field($modelResidentialAssociation, 'treasurer_name')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('Treasurer Name');?>
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
                              <?=$form->field($modelResidentialAssociation, 'no_of_households_in_association')->textInput(['value' => '','maxlength' => true,'class'=>'form-control form-control-line'])->label('No of households in association');?>
                            </div>
                          </div>
                        </div>
                      </div>
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

$title = isset($title)?$title:'Success';
$type = isset($type)?$type:'success';
$message = isset($message)?$message:'Residential association has been added successfully';
$title = Html::encode(trim($title));
$message = Html::encode(trim($message));
$title =  $title;
$message =  $message;
if (isset($saved) && $saved == 1):
  $this->registerJs("
  swal({title:'Success',text: '$message', type:'$type'});
  $.pjax.reload('#pjax-residential-ass-list');
  ");
endif ;

?>
