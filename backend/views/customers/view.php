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
use backend\models\ResidentialAssociation;
  $modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
foreach($params as $param => $val)
  ${$param} = $val;
$coordsSet = isset($params['coordsSet'])?$params['coordsSet']:true;
$mapsApiKey = Yii::$app->params['google-maps-api-key'];
$this->registerJsFile("//code.jquery.com/jquery-1.11.3.min.js");
$this->registerJsFile("https://maps.googleapis.com/maps/api/js?key=$mapsApiKey&libraries=places&callback=initAutocomplete",['defer'=>'true']);
$this->registerJsFile("../../js/locationpicker.jquery.js");
$latCustomer = $model->lat?$model->lat:'';
    $lngCustomer =$model->lng?$model->lng:'';
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Customers</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Customers', 'url' => ['/customers/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
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
        <?php if(Yii::$app->user->can('Customers-update')||$userRole=='super-admin'):
    ?>
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Update Info</a></li>
          <?php endif;?>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Payments
</a></li>
          <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Services
</a></li>
<li><a href="#tab_4" data-toggle="tab" aria-expanded="false">Assign Gt
</a></li>
<?php if(isset($model->fkBuildingType->fkCategory->rate_type)&&$model->fkBuildingType->fkCategory->rate_type==1):?>
<li><a href="#tab_5" data-toggle="tab" aria-expanded="false">Slab Service
</a></li>
<?php endif;?>
        </ul>
        <div class="tab-content" style="margin-left: 18px;">
         <?php if(Yii::$app->user->can('Customers-update')||$userRole=='super-admin'):
    ?>
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'pjax-view-customer-add','options'=>['data-loader'=>'.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
                    ?>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $model->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->ward_id)])->label('District')?>
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
                         'data'=>[$model->getConstituency($model->ward_id)],
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
                         'data'=>[$model->getBlock($model->ward_id)],
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
                  'data'=>[$model->getLsgis($model->ward_id)],
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
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($model, 'ward_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                'data'=>[$model->getWard($model->ward_id)],
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
    <div class="row">
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                  <?php $type= BuildingType::getType();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($model, 'building_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                        <?= $form->field($model, 'door_status')->dropDownList([1=>'Open',0=>'Closed',2=>'Permanently locked'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
        </div>
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
        <?php $association= ResidentialAssociation::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($association, 'id', 'name');
                    echo $form->field($model, 'residential_association_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'association-id'])->label('Residential Association')?>
                  </div>
                </div>
              </div>
            
         
           <!--  <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                        <?= $form->field($model, 'association_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div> -->
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
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?php $type= TradingType::getType();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($model, 'trading_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?php $type= ShopType::getType();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($model, 'shop_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'has_bio_waste')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'has_non_bio_waste')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                  </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'has_disposible_waste')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
          </div>

    <?= $form->field($model, 'lat')->textInput(['id'=>'latitude1']) ?>

    <?= $form->field($model, 'lng')->textInput(['id'=>'longitude1']) ?>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $interval= FeeCollectionInterval::getInterval();
                    $listData=ArrayHelper::map($interval, 'id', 'name');
                    echo $form->field($model, 'fee_collection_interval_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  
                   <!-- $form->field($model, 'bio_waste_management_facility_operational')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line']) -->
                  
                  <?= $form->field($model, 'bio_waste_management_facility_operational')->dropDownList([0=>'Yes',1=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'bio_waste_management_facility_repair_help_needed')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= WasteCollectionMethod::getMethod();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'bio_waste_collection_method_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'bio_waste_collection_needed')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= WasteCollectionMethod::getNonBioMethod();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'non_bio_waste_collection_method_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_terrace_farming_interest')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= TerraceFarmingHelpType::getType();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'terrace_farming_help_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'people_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'house_adult_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'house_children_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($modelAccount, 'parent_id')->textInput(['class'=>'form-control form-control-line'])->label('Parent account') ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                 <?= $form->field($model, 'daily_bio_waste_quantity')->textInput(['class'=>'form-control form-control-line'])->label('Daily Bio Waste Quantity') ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_non_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'market_visiters_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'seating_capacity')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'monthly_booking_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'house_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?php $method= PublicPlaceType::getType();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'public_place_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Public Place Type');?>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= PublicGatheringMethods::getMethod();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'public_gathering_method')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Public Gathering Method');?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?= $form->field($model, 'is_programmes_happening')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'public_place_area')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'office_contact_person')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'office_contact_person_designation')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                 <?php $method= OfficeType::getType();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'office_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Office Type');?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?= $form->field($model, 'daily_collection_needed_bio')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'shop_name')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'licence_no')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'employee_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'space_available_for_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'help_needed_for_bio_waste_management_facility_construction')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'building_in_use')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_space_for_non_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
            <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'space_available_for_non_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_interest_for_allotting_space_for_non_bio_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'has_interest_in_bio_waste_management_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'green_protocol_system_implemented')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'bio_medical_waste_collection_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?= $form->field($model, 'has_bio_medical_incinerator')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= WasteCollectionMethod::getMethod();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'bio_medical_waste_collection_method')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'building_area')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            </div>
             <div class="row">
          <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'has_public_program_option')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'lead_person_designation')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= AdministrationType::getType();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'administration_type')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'public_program_count')->textInput(['class'=>'form-control form-control-line']) ?>
                </div>
              </div>
            </div>
            </div>
             <div class="row">
            <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?php $method= BuildingTypeSubTypes::getType();
                    $listData=ArrayHelper::map($method, 'id', 'name');
                    echo $form->field($model, 'building_sub_type')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
             <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?= $form->field($model, 'has_interest_in_system_provided_bio_facility')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
              </div>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'has_public_toilet')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                       <?= $form->field($model, 'public_toilet_count_men')->textInput(['class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'public_toilet_count_women')->textInput(['class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                       <?= $form->field($model, 'public_toilet_count')->textInput(['class'=>'form-control form-control-line']) ?>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
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
              
            </div>
          <div class = 'col-md-8'>
 <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Customer Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$latCustomer?>,<?=$lngCustomer?>&hl=es;z=14&output=embed"></iframe>
            </div>  

  <div class="x_panel">
<?php
 ?>

  </div>
<?php

// $iconUrl = Url::to(Yii::$app->params['marker-image']);
$this->registerJs("

var icon = iconBase + 'arrow.png';
console.log(icon);
var marker = null;
$(document).ready(function() {
    //GetAddress();
    //GetLocation();
    var map = initAutocomplete();
    google.maps.event.addListener(map, 'idle', function()
    {
      google.maps.event.trigger(map, 'resize');
    });
    map.setZoom( map.getZoom() - 1 );
    map.setZoom( map.getZoom() + 1 );

 ;
});
$('#map').on('click', function() {
    GetAddress();
});
function initPlacesInput(map) {
  var input = document.getElementById('place');
  var searchBox = new google.maps.places.SearchBox(input);
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  /*map.addListener('bounds_changed', function(e) {
      searchBox.setBounds(map.getBounds());
  });*/
  var markers = [];

  searchBox.addListener('places_changed', function() {

      var places = searchBox.getPlaces();
      if (places.length == 0) {
          return;
      }

      markers.forEach(function(marker) {
          marker.setMap(null);
      });
      markers = [];
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
          /*var icon = {
              url: icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
          };*/

          markers.push(new google.maps.Marker({
              map: map,
              icon:icon,
              draggable:true,
              title: place.name,
              position: place.geometry.location
          }));
          console.log(place);
          fillAddressInputs(place)
          var latitude = place.geometry.location.lat();
          var longitude = place.geometry.location.lng();
          $('#latitude').val(latitude);
          $('#longitude').val(longitude);

          if (place.geometry.viewport) {
              bounds.union(place.geometry.viewport);
          } else {
              bounds.extend(place.geometry.location);
          }
          GetLocation();

      });
      map.fitBounds(bounds);
  });
}
function assignMapClick(map) {
  google.maps.event.addListener(map, 'click', function(e) {
      var latLng = e.latLng;
      var latitude = e.latLng.lat();
      var longitude = e.latLng.lng();
      $('#latitude').val(latitude);
      $('#longitude').val(longitude);
      if (marker) {
          marker.setPosition(latLng);
      } else {
          marker = new google.maps.Marker({
              position: latLng,
              map: map,
              draggable: true,
              icon:icon
          });
      }
  });
}
function initAutocomplete() {
    geocoder = new google.maps.Geocoder();
    //console.log(geocoder);
    if ((document.getElementById('latitude1').value && (document.getElementById('longitude1').value))) {
        var laty = parseFloat(document.getElementById('latitude1').value);
        var lngy = parseFloat(document.getElementById('longitude1').value);
    }
    if (laty && lngy) {
        var laty = laty;
        var lngy = lngy;
    } else {
        var laty = 9.9564;
        var lngy = 76.3015;
    }
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: laty,
            lng: lngy
        },
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    marker = new google.maps.Marker({
        map: map,
        icon:icon,
        draggable:true,
        animation: google.maps.Animation.DROP,
        position: {
            lat: laty,
            lng: lngy
        },
    });

    assignMapClick(map);
    initPlacesInput(map);
    return map;
}

function fillAddressInputs(place) {
  var addrLn1  = $('#address-line1').val().trim();
  var name = place.name?place.name.trim():addrLn1;
  var vicinity = place.vicinity?place.vicinity.trim():'';
  vicinity = vicinity.split(',');
  addressComponents = place.address_components;
  $('#place').val('');
  $('#city').val('');
  $('#district').val('');
  $('#state').val('');
  $('#pincode').val('');
  for (var x = 0; x < addressComponents.length; x++) {
      var chk = addressComponents[x];
      var curPlaceVal  =  $('#place').val();
      var curCityVal = $('#city').val();
      if (chk.types[0] == 'postal_code') {
          zipCode = chk.long_name;
          $('#pincode').val(zipCode);
      } else
      if (chk.types[0] == 'locality') {
          locality = chk.long_name;
          $('#city').val(locality);
          if(curPlaceVal=='')
          $('#place').val(locality);
      } else
      if (chk.types[0] == 'administrative_area_level_2') {
          district = chk.long_name;
          $('#district').val(district);
      } else
      if (chk.types[0] == 'administrative_area_level_1') {
          state = chk.long_name;
          $('#state').val(state);
      }
      else
      if (chk.types.indexOf('sublocality')  !=-1 || chk.types.indexOf('sublocality_level_1') != -1) {
          loc = chk.long_name;
          $('#place').val(loc);

      } else {
        if(curPlaceVal=='') {
          var city = $('#city').val();
          $('#place').val(city);
        }
        if(curCityVal == '') {
          var district = $('#district').val();
          $('#city').val(district);

        }
      }
      curPlaceVal = $('#place').val();
      curCityVal = $('#city').val();
      var curDistrictVal = $('#district').val();
      if(curPlaceVal == curCityVal) {
        $('#city').val(curDistrictVal);
      }

  }
  
  var city = $('#city').val().toString().toLowerCase();

  var locality = $('#place').val().toString().toLowerCase();
  var district = $('#district').val().toString().toLowerCase();
  var state = $('#state').val().toString().toLowerCase();
  var pincode = $('#pincode').val().toString().toLowerCase();
  var itemsToExclude = [locality,city,district,state,pincode];
  var itemsToInclude  =[];
  var item = null;
  for(var idx in vicinity) {
    item = vicinity[idx].trim().toString().toLowerCase();
    if(itemsToExclude.indexOf(item)==-1) {
      itemsToInclude.push(item);
    }
  }
  var addrLine2 = itemsToInclude.join(',');
  // $('#address-line2').val(addrLine2);
}
function GetAddress() {
    var lat = parseFloat(document.getElementById('latitude').value);
    var lng = parseFloat(document.getElementById('longitude').value);
    var latlng = new google.maps.LatLng(lat, lng);
    /* $.ajax({ url:'http://maps.googleapis.com/maps/api/geocode/json?latlng=lat,lng&sensor=true',
         success: function(data){
             alert(data.results[0].formatted_address);

         }
    }); */
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'latLng': latlng
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                var add = results[1].formatted_address;
                var value = add.split(',');


                count = value.length;
                //console.log(count);
                country = value[count - 1];
                state = value[count - 2];
                city = value[count - 3];
                place = value[count - 4];
                var addressComponents = results[0].address_components;
                console.log(results[0]);
                fillAddressInputs(results[0]);
                //console.log(results[1].formatted_address);
            }
        }
    });
}

function GetLocation() {
    var lat = parseFloat(document.getElementById('latitude').value);
    var lng = parseFloat(document.getElementById('longitude').value);
    // var latlng = new google.maps.LatLng(lat, lng);

    var myLatlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 13,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('map'), myOptions);
    marker = new google.maps.Marker({
        position: myLatlng,
        draggable:true,
        map: map,
        icon:icon,
        title: 'Fast marker'
    });

    marker.setPosition(myLatlng);
    assignMapClick(map);


}


", View::POS_END);



 ?>


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
        <?php endif;?>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                   <?=$this->render('fee/fee-paid', [
                        'model'=> $model,
                        'modelAccountFee' => $modelAccountFee,
                        'dataProvider' => $dataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_3">
                   <?=$this->render('service/service', [
                        'model'=> $model,
                        'modelAccountService' => $modelAccountService,
                        'serviceDataProvider' => $serviceDataProvider,
                        ]);?>
                </div>
                 <div class="tab-pane" id="tab_4">
                   <?=$this->render('gt/gt', [
                        'model'=> $model,
                        'modelAccount'=> $modelAccount,
                        'modelAccountAuthority' => $modelAccountAuthority,
            'authorityDataProvider' => $authorityDataProvider,
                        ]);?>
                </div>
                <?php if(isset($model->fkBuildingType->fkCategory->rate_type)&&$model->fkBuildingType->fkCategory->rate_type==1):?>
                <div class="tab-pane" id="tab_5">
                   <?=$this->render('slab/slab-service', [
                        'model'=> $model,
                        'modelAccount'=> $modelAccount,
                        'modelAccountSlabService' => $modelAccountSlabService,
                        'slabAccountServiceDataProvider' => $slabAccountServiceDataProvider,
                        ]);?>
                </div>
              <?php endif;?>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
