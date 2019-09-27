<?php

    use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\WasteCategory;
use yii\web\View;
    foreach ($params as $param => $val)
    {
        ${
            $param} = $val;
    }
    $lat = $model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:'';
    $lng =$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:'';
    $latCustomer = $model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lat:'';
    $lngCustomer =$model->fkAccount->fkCustomer?$model->fkAccount->fkCustomer->lng:'';
$link = "/wastemanagement/backend/web/customers/view-details?id=".$model->fkAccount->fkCustomer->id;
$status = '';
if($model->fkServiceAssignment)
{
  if($model->fkServiceAssignment->door_status==1)
    $status = 'Open';
  else
    $status = ' Closed';
}
$marked = $model->marked_rating_value?$model->marked_rating_value:0;
$total = $model->total_rating_value?$model->total_rating_value:0;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Complaints</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
       $breadcrumb[]                  = ['label' => 'Complaints', 'url' => ['/requests/complaints']];
       $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Green Action Unit'), 'url' => ['index']];
           $this->title = 'Service Request';
       $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs', ['links' => $breadcrumb]);?>
 </div>
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Complaint Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Assign Gt</a></li>
            <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">Complaint Status</a></li>
        </ul>
        <div class="tab-content" style="margin-left: 18px;height: 806px;">
            <div class="tab-pane active" id="tab_1">
         <div class = 'col-md-6' style="overflow:hidden !important">
        <div class="panel panel-default" >
        <div class="panel-heading"><b>Basic Informations</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Customer</b></td><td><a target="_blank" data-pjax=0 href=<?=$link?>><?=$model->fkAccount->fkCustomer->lead_person_name?></a></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->fkAccount->fkCustomer->address?></td></tr>
        <tr><td><b>Phone</b></td><td><?=$model->fkAccount->fkCustomer->lead_person_phone?></td></tr>
        <tr><td><b>Service</b></td><td><?=$model->fkService->name?></td></tr>
        <tr><td><b>Customer Remarks</b></td><td><?=$model->remarks?$model->remarks:''?></td></tr>
        <tr><td><b>Requested Date</b></td><td><?=$model->requested_datetime?></td></tr>
        <tr><td><b>Door Status</b></td><td><?=$status?></td></tr>
        <tr><td><b>Green Technician</b></td><td><?=$model->fkServiceAssignment?$model->fkServiceAssignment->fkAccount->fkPerson->first_name:''?></td></tr>
        <tr><td><b>Remarks</b></td><td><?=$model->fkServiceAssignment?$model->fkServiceAssignment->remarks:''?></td></tr>
        <tr><td><b>Service Status</b></td><td><?=$model->getStatus()?></td></tr>
        <tr><td><b>Rating</b></td><td><?=$marked.'/'.$total?></td></tr>
        </table>
        </div>
        </div>
       <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Marked Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$lat?>,<?=$lng?>&hl=es;z=14&output=embed"></iframe>
            </div>
             <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Customer Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$latCustomer?>,<?=$lngCustomer?>&hl=es;z=14&output=embed"></iframe>
            </div>
            </div>
            <div class="tab-pane" id="tab_2">
                    <?=$this->render('assign-gt', [
    'model'                      => $model,
    'modelServiceAssignment' => $modelServiceAssignment
]);?>
                </div>
                 <div class="tab-pane" id="tab_3">
                    <?=$this->render('assign-status', [
    'model'                      => $model,
    'modelServiceAssignment' => $modelServiceAssignment
]);?>
                </div>
            </div>
            </div>
            </div>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['add-gt','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  ?>
    <?= $form->field($modelServiceAssignment, 'lat_update_from')->hiddenInput(['id'=>'latitude1','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lat_update_from:''])->label(false) ?>

    <?= $form->field($modelServiceAssignment, 'lng_updated_from')->hiddenInput(['id'=>'longitude1','value'=>$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:''])->label(false) ?>
      <?php
   ActiveForm::end();
  ?>         
 <style>
  td, th {
    padding: 10px;
}
</style>
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
        var laty = null;
        var lngy = null;
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