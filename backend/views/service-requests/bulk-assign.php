<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Bulk Assign</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Bulk Assign', 'url' => ['/service-requests/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bulk Assign'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->activity_name);
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
    <?php $form = ActiveForm::begin(['action' => ['bulk-assign-gt'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);?>
    <div class=row>
         <div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
        <?php $district= $model->getDistrict();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id'])->label('District')?>
                  </div>
                </div>
              </div>
        </div>
        <div class=row>
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($model, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        // 'data'=>[$model->getConstituency($model->lsgi_id)],
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
                        // 'data'=>[$model->getBlock($model->lsgi_id)],
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
          <div class=row>
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 // 'data'=>[$model->getLsgis($model->lsgi_id)],
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
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?php

            echo $form->field($model, 'ward_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                // 'data'=>[$model->getWard($model->ward_id)],
                'options'=>['id'=>'ward-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/wards/get-wards'])
                ]
            ])->label('Ward');
            ?>
                </div>
            </div>
        </div>
        </div>
        <div class=row>
         <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'account_id_gt')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 // 'data'=>[$modelAccount->getSupervisor($modelAccount->supervisor_id)],
                'options'=>['id'=>'gt-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['ward-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/account/gt'])
                ]
            ])->label('Green Technician');
            ?>
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
</div>
    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
