<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Camera */


$this->title = Yii::t('app', 'Create Camera');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cameras'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
// print_r($saved);exit;

?>
<div class="camera-create">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Camera', 'url' => ['/camera/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camera'), 'url' => ['index']];
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
    <div class="camera-form">

      <?php $form = ActiveForm::begin(['action' =>['create'],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <div class="row">
                <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                  <label for="eh-first-name">Serial No.</label>
                  <?= $form->field($model, 'serial_no')->textInput(['class' => 'form-control','placeholder' => 'Serial No.'])->label(false); ?>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-12">
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
                <label for="eh-first-name">Camera name</label>
                <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Camera name'])->label(false); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-12">
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
               <?php
                $district= $model->getDistricts();
                 $listData=ArrayHelper::map($district, 'id', 'name');
                 echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$model->getDistrict($model->lsgi_id)])->label('District')?>
               </div>
             </div>
           </div>
           <div class="col-lg-12 col-md-12 col-sm-6 col-12">
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
        </div></div></div>
         </div>
         <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-6 col-12">
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
         </div>
         <div class="col-lg-12 col-md-12 col-sm-6 col-12">
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
         ])->label('Lsgi');
         ?>
                 </div>
             </div>
         </div>
       </div>
       <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-6 col-12">
           <div class="row">
             <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
               <?php $wardList = ArrayHelper::map($modelWard,'id','name'); ?>
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
               // echo $form->field($model, 'ward_id')->widget(Select2::classname(), [
               //   'data' => $wardList,
               //   'language' => 'de',
               //   'options' => ['placeholder' => 'Select Ward'],
               //   'pluginOptions' => [
               //   'allowClear' => true
               //   ],
               //   ])->label(false);?>
             </div>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-6 col-12">
           <div class="row">
             <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
               <!-- <label for="eh-first-name">Account Technician</label> -->
               <?php

                   echo $form->field($model, 'account_id_technician')->widget(DepDrop::classname(), [
                       'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$model->getAccountTechnician($model->account_id_technician)],
                       'options'=>['id'=>'account-technician-id','class'=>'form-control form-control-line'],
                       'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                       'pluginOptions'=>[
                          'depends'=>['ward-id'],
                           'placeholder'=>'Select...',
                           'class'=>'form-control form-control-line',
                           'url'=>Url::to(['/camera/account-technician'])
                       ]
                   ]);
                 // $technicianList = ArrayHelper::map($modelAccountList,'id','first_name');
                 // echo $form->field($model, 'account_id_technician')->widget(Select2::classname(), [
                 // 'data' => $technicianList,
                 // 'language' => 'de',
                 // 'options' => ['placeholder' => 'Select Technician'],
                 // 'pluginOptions' => [
                 // 'allowClear' => true
                 // ],
                 // ])->label(false);
               ?>
             </div>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
           <label for="eh-first-name">Lat</label>
           <?= $form->field($model, 'lat')->textInput(['class' => 'form-control','placeholder' => 'Lat'])->label(false); ?>
         </div>
       </div>
       <div class="row">
         <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
           <label for="eh-first-name">Lng</label>
           <?= $form->field($model, 'lng')->textInput(['class' => 'form-control','placeholder' => 'Lng'])->label(false); ?>
         </div>
       </div>
      <div class="row">
        <div class="col-lg-12 ta-right">
          <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button>
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>
        <?php ActiveForm::end(); ?>

    </div>

    <!-- <script>
      var map, locMap;
      function initMap() {

        var uluru = {lat: 25.3058644, lng: 49.5432736};
        map = new google.maps.Map(document.getElementById('camera-add-map'), {
          center: uluru,
          zoom: 7,
          disableDefaultUI: true,
          styles:
          [
            {
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#f5f5f5"
                }
              ]
            },
            {
              "elementType": "labels.icon",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#616161"
                }
              ]
            },
            {
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#f5f5f5"
                }
              ]
            },
            {
              "featureType": "administrative.land_parcel",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#bdbdbd"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#eeeeee"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#757575"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#e5e5e5"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#ffffff"
                }
              ]
            },
            {
              "featureType": "road.arterial",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#757575"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#dadada"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#616161"
                }
              ]
            },
            {
              "featureType": "road.local",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            },
            {
              "featureType": "transit.line",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#e5e5e5"
                }
              ]
            },
            {
              "featureType": "transit.station",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#eeeeee"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#c9c9c9"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            }
          ]
        });

        var marker = new google.maps.Marker({
          position: uluru,
          map: map,
          icon: '../images/locations.png'
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnG4QUmKyu5PVqlMjlYnml5KAht7eVtow&callback=initMap"
    async defer></script> -->


</div>
