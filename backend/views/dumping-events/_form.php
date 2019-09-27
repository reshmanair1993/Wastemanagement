<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Service;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
$modelUser  = Yii::$app->user->identity;
  $userRole = $modelUser->role;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Dumping Event</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Dumping Event', 'url' => ['/dumping-events']];
   $this->title = Yii::t('app', 'Create Dumping Event');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
if($model->id){
   $this->title =  'Update';
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
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
               <div class="row">
        <?php if($userRole!='supervisor'):?>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                $listData=ArrayHelper::map($modelPerson, 'id', 'first_name');
                echo $form->field($model, 'supervisor')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>''])->label('Supervisor');
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                echo $form->field($model, 'account_id_customer')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'options'=>[
                    'id'=>'service-customer-id',
                    'class'=>'form-control form-control-line',
                  ],
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                    'depends'=>['supervisor-person-id'],
                    'class'=>'form-control form-control-line',
                    'placeholder'=>'Select...',
                    'url'=>Url::to(['account-service-requests/get-services-customer'])
                  ]
                  ])->label('Customer Name');
                  ?>
              </div>
            </div>
          </div>
        <?php else:?>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
                <?php
                echo $form->field($model, 'supervisor')->hiddenInput(['class'=>'form-control form-control-line','id'=>'supervisor-person-id','value'=>$modelUser->id])->label(false);
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
               <?php
              $list=Service::getCustomer($modelUser->id);
              $listData=ArrayHelper::map($list, 'id', 'lead_person_name');

            echo $form->field($model, 'account_id_customer')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-customer-id'])->label('Customer Name')
                  ?>
              </div>
            </div>
          </div>
        <?php endif;?>
          
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $type= $model->getTypes();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($model, 'incident_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'type-id'])->label('Incident Type')?>
                  </div>
                </div>
              </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'lat')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Latitude');?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'lng')->textInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('Longitude');?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'remarks')->textArea();?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-12 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
