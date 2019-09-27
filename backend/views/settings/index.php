<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Image;
use yii\web\JsExpression;
$modelImage = new Image;

foreach($params as $param => $val)
  ${$param} = $val;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Settings</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Settings', 'url' => ['/settings/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'settingss'), 'url' => ['index']];
if($modelSettings->id){
   $this->title =  '';
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
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Settings Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Social media</a></li>
        </ul>
        <div class="tab-content" style="    margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-settings-edit','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['index'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
     <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSettings, 'address_en')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSettings, 'address_ml')->textArea(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSettings, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSettings, 'contact_number')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Choose Location</label>
    <?= $form->field($modelSettings, 'location_name')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'location'])->label(false) ?>
  </div>
  </div>
  </div>
  <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Latitude</label>
    <?= $form->field($modelSettings, 'lat')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'up-lat'])->label(false) ?>
  </div>
  </div>
  </div>
  <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Longitude</label>
    <?= $form->field($modelSettings, 'lng')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'up-lng'])->label(false) ?>
  </div>
  </div>
  </div>
<div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
<?php
echo $form->field($modelSettings, 'map_input')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
  'key' => 'AIzaSyBnG4QUmKyu5PVqlMjlYnml5KAht7eVtow',
  'valueTemplate' => '{latitude},{longitude}',
  'options' => [
    'style' => 'width: 100%; height: 400px; border: 1px solid #FF001F',
    ] ,
    'enableSearchBox' => true ,
    'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
    'mapOptions' => [
      'mapTypeControl' => true,
      'mapTypeControlOptions' => [
        'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
      ],
      'streetViewControl' => true,
    ],
    'clientOptions' => [
      'location' => [
                'latitude'  => $modelSettings->lat,
                'longitude' => $modelSettings->lng,
            ],
      'radius'    => 300,
      'addressFormat' => 'street_number',
      'inputBinding' => [
        'latitudeInput'     => new JsExpression("$('#up-lat')"),
        'longitudeInput'    => new JsExpression("$('#up-lng')"),
        'locationNameInput' => new JsExpression("$('#location')")
      ],
    ],
  ]);
  ?>
  </div>
  </div>
  </div>
   </div>
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>
        
                  <?php  ActiveForm::end();
                    Pjax::end();
                ?>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('social-media', [
                        'modelSettings'=> $modelSettings,
                        'modelSocialMedia'=> $modelSocialMedia,
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
