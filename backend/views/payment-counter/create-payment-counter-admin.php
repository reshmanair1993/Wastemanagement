<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use backend\models\Camera;


/* @var $this yii\web\View */
/* @var $model backend\models\MonitoringGroup */

$this->title = Yii::t('app', 'Create Payment Counter Admin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Counter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$modelUser = Yii::$app->user->identity;
$userRole  = $modelUser->role;
?>
<div class="payment-counter-create">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Payment Counter', 'url' => ['/payment-counter/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Counter Admin'), 'url' => ['index']];
  if($model->id){
     $this->title =  'Create Payment Counter Admin';
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

    <div class="group-form">

      <?php $form = ActiveForm::begin(['action' =>['create-payment-counter-admin'],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Payment Counter Admin Name</label>
              <?= $form->field($modelPerson, 'first_name')->textInput(['class' => 'form-control','placeholder' => 'Counter Admin name'])->label(false); ?>
            </div>
          </div>
          <?php if (!($userRole == 'admin-lsgi')){ ?>
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
             <?php
              $district= $model->getDistricts();
               $listData=ArrayHelper::map($district, 'id', 'name');
               echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->lsgi_id)])->label('District')?>
            </div>
          </div>
           <div class="row">
             <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
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
           <div class="row">
             <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
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
                 <div class="row">
                   <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">

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
               ]);
               ?>
                       </div>
                   </div>
                   <div class="row">
                     <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                       <?php
                       echo $form->field($modelAccountWard, 'ward_id')->widget(DepDrop::classname(), [
                           'type'=>DepDrop::TYPE_SELECT2,
                           'data'=>[$model->getWard($model->lsgi_id)],
                           'options'=>['id'=>'ward-id','class'=>'form-control form-control-line'],
                           'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                           'pluginOptions'=>[
                              'depends'=>['lsgi-id'],
                               'placeholder'=>'Select...',
                               'class'=>'form-control form-control-line',
                               'url'=>Url::to(['/wards/get-wards'])
                           ]
                       ])->label('Ward');?>
              </div>
            </div>
                 <?php }
                 else{ ?>

                 <div class="row">
                   <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                     <?php
                      $ward = Camera::getWards($modelUser->lsgi_id);
                      $listData = ArrayHelper::map($ward, 'id', 'name');
                      echo $form->field($modelAccountWard, 'ward_id')->widget(Select2::classname(), [
                      'data' => $listData,
                      'language' => 'de',
                      'options' => [
                        'placeholder' => 'Select Counter',
                        'id' => 'payment_counter_id'
                      ],
                      'pluginOptions' => [
                        'placeholder'=>'Select...',
                        'allowClear' => true
                      ],
                      ])->label('Ward');
                     ?>
            </div>
          </div>
        <?php }?>
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Email</label>
                <?= $form->field($modelPerson, 'email')->textInput(['class' => 'form-control','placeholder' => 'Email'])->label(false); ?>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Password</label>
                <?= $form->field($model, 'password_hash')->passwordInput(['class' => 'form-control','placeholder' => 'Password'])->label(false); ?>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Re-type Password</label>
                <?= $form->field($model, 'compare_password')->passwordInput(['class' => 'form-control','placeholder' => 'Password'])->label(false); ?>
              </div>
            </div>
      <div class="row">
        <div class="col-lg-12 ta-right">
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <a href="<?=Url::to(['/payment-counter/index'])?>" class="btn btn-success">cancel</a>
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>

    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
