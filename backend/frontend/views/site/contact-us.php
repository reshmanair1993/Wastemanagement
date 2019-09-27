<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
  <section class="page-banner contact-banner">
    <img src="../img/contact/banner.jpg" alt="Smart Trivandrum Banner" class="bg-img">
    <div class="container">
      <div class="contact-holder">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <ul>
              <li><img src="../img/contact/user.png" alt="">Address</li>
              <li><?=$modelSettings->address_en?></li>
            </ul>
            <ul>
              <li><img src="../img/contact/user.png" alt="">Email</li>
              <li><?=$modelSettings->email?></li>
            </ul>
            <ul>
              <li><img src="../img/contact/user.png" alt="">Customer support</li>
              <li><?=$modelSettings->contact_number?></li>
            </ul>
          </div>
          <div class="col-lg-8 col-md-8 col-sm-12 col-12">
           <?php $form = ActiveForm::begin(['id' => 'contact-form','action'=>'contact-us']); ?>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','placeholder'=>'Name']);?>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <?=$form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','placeholder'=>'Email']);?>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                   <?=$form->field($model, 'phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','placeholder'=>'Phone']);?>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <?=$form->field($model, 'message')->textArea(['maxlength' => true,'class'=>'form-control form-control-line','placeholder'=>'Message']);?>
                  </div>
                </div>
              </div>
              <button type="submit" class="bt-primary">send</button>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="map-holder">
    <div id="map"></div>
  </section>


  <script src="./js/jquery.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <!-- <script src="./js/script.js"></script> -->
  <script>
  
    $("#toggle").click(function() {
      $(this).toggleClass("on");
      $("#menu").slideToggle();
    });

    var map;
    var image = "../img/contact/location.png";
    var LatLng  = {lat: 41.1614954, lng: -81.2533256};
    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        center: LatLng,
        zoom: 12,
        styles:[
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
        position: LatLng,
        map: map,
        icon: image
      });
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9Z2NbAjbAGmTnxhcbHkbr0Wc_iwPUuNQ&callback=initMap" async defer></script>
  </body>
  </html>
