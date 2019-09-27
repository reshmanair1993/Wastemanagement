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
use backend\models\BuildingType;
use backend\models\PublicPlaceType;
use backend\models\TradingType;
use backend\models\OfficeType;
use backend\models\AdministrationType;
use backend\models\BuildingTypeSubTypes;
use backend\models\ShopType;
use backend\models\FeeCollectionInterval;
use backend\models\WasteCollectionMethod;
use backend\models\TerraceFarmingHelpType;
use backend\models\PublicGatheringMethods;
  $modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Customers</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Customers', 'url' => ['/customers/profile']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['profile']];
if($model->id){
   $this->title =  ucfirst($model->lead_person_name);
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
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Payments
</a></li>
        </ul>
        <div class="tab-content" style="margin-left: 18px;">
          <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-edit-customer','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['edit','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                <?= $form->field($model, 'building_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                            </div>
                        </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
    <?= $form->field($model, 'building_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                           </div>
                        </div>
            </div>
            </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                        <?= $form->field($model, 'association_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'association_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                  </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                        <?= $form->field($model, 'lead_person_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'lead_person_phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'address')->textarea(['rows' => 6,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'building_owner_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'building_owner_phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
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
          <div class="tab-pane" id="tab_2">
                   <?=$this->render('fee/fee-paid', [
                        'model'=> $model,
                        'modelAccountFee' => $modelAccountFee,
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
