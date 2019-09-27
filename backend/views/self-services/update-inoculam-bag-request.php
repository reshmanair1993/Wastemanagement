<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use backend\models\Service;
use backend\models\ResidentialAssociation;
use backend\models\Mrc;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
  $modelUser  = Yii::$app->user->identity;
  $userRole = $modelUser->role;
  $count = 0;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Inoculam Bag Request</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Inoculam Bag Request', 'url' => ['self-services/inoculam-bags-requests']];
   $this->title =  'Create';

$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => ['update-inoculam-bag-request','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);?>
        <div class="row">
         <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                    <div class="col-sm-7 col-xs-4">
                        <?php $mrc=Mrc::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($mrc, 'id', 'name');
                    echo $form->field($model, "mrc_id")->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Mrc')?>
                    </div>
                </div>
            </div>
          <div class="col-sm-4 col-xs-4">
            <div class="form-group">
              <div class="col-sm-4 col-xs-4">
                <?php
                echo $form->field($model, "qty")->textInput(['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Quantity');
                ?>
              </div>
            </div>
          </div>
         
      <div class="col-sm-3 col-xs-3">
          <div class="form-group">
            <div class="col-sm-3 col-xs-3">
            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']);?>
            </div>
          </div>
        </div>
        <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</div>
</div>
<?php
$this->registerJs("
$('.datepicker').datepicker({
         orientation:'top',
         format:'dd-mm-yyyy',
         autoclose:true,
         todayHighlight:true,
     });
$('#reverificationBtn').on('click', function() {
      $('.reverification').show();
    });
    $(document).ready(function(){
      $('.reverification').hide();
      });
",View::POS_END);
?>
