<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Camera */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="camera-form">

  <?php $form = ActiveForm::begin(['action' =>['create','id' =>$model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-6 col-12">
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Serial No.</label>
              <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Serial No.'])->label(false); ?>
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
              <label for="eh-first-name">Ward</label>
              <?php $wardList = ArrayHelper::map($modelWard,'id','name'); ?>
              <?= $form->field($model, 'ward_id')->dropDownList($wardList,['prompt'=>'Select Ward','class'=>'form-control',])->label(false) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Location</label>
          <div id="camera-add-map"></div>
        </div>
      </div>
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

<script>
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
async defer></script>
