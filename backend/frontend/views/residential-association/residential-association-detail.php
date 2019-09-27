<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Ward;
use yii\widgets\ListView;
use yii\helpers\Url;
foreach($params as $param => $val)
  ${$param} = $val;
?>
<section class="page-banner banner-content">
  <img src="../img/residential/banner.jpg" alt="Smart Trivandrum Banner" class="bg-img">
  <div class="container ta-center">
    <div class="detail-holder">
      <div class="detail-head">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <h3><?=$model->name?></h3>
            <span><?=$model->fkWard->name?></span><br>
            <a href="#" class="call"><img src="../img/residential/call-icon.png" alt=""><?=$model->phone1?></a>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#"><?=$model->fkWard->fkLsgi->name?></a></li>
                <li class="breadcrumb-item"><a href="#"><?=$model->fkWard->name?></a></li>
                <li class="breadcrumb-item"><a href="#">Residential Association</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$model->name?></li>
              </ol>
            </nav>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-12 ta-right">
            <a href="<?=Yii::$app->params['backend_public_url']?>" class="bt-transparent"><img src="../img/residential/login.png" alt="">Login</a>
          </div>
        </div>
      </div>
      <div class="detail-content">
        <h3>About</h3>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
          text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <h3>Stakeholders</h3>
        <div class="row">
          <?php foreach ($dataProvider->getModels() as $value)
         {?>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
              <div class="contact-box">
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-4 p-0">
                   <img src="<?=$value->getProfileUrl()?>" / style="vertical-align: middle;border-style: none; width: 100%; height: 118px; object-fit: cover;">
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 p-0">
                    <div class="officials">
                      <h4><?=$value->position?></h4>
                      <ul>
                        <li><img src="../img/residential/user.png" alt=""><?=$value->name?></li>
                        <li><img src="../img/residential/contact.png" alt=""><a href="#"><?=$value->phone?></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="row m-top">
          <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <h3>Houses <span>( 6 out of <?=$customerDataProvider->getCount()?>)</span></h3>
          </div>
          <?php $form = ActiveForm::begin(['action' =>Url::to(['residential-association/details','id'=>$model->id,'set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
          <div class="col-lg-8 col-md-12 col-sm-12 col-12">
            <div class="form-group row">
              <div class="col-lg-9 col-md-8 col-sm-8 col-12">
                 <?php $keyword =  isset($_POST['keyword'])?$_POST['keyword']:'';?>
                <input type="text" name="keyword" value="<?php if (isset($keyword)) echo $keyword; ?>"  placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-4 col-12">
                <button type="submit" class="bt-primary">Search</button>
              </div>
            </div>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
        <table class="table table-responsive">
    <tbody>
  <?php
    echo ListView::widget([
      'pager'        => [

                                       'firstPageLabel'    => "",
                                       'disabledPageCssClass'=>'swiper-button-disabled',
                                       'lastPageLabel'     => "",
                                       'nextPageLabel'     => ">",
                                       'prevPageLabel'     => "<",
                                       'maxButtonCount'=>0,
                                       'nextPageCssClass'=>'total-button-next',                                     'prevPageCssClass'=>'total-button-prev',
                                       'options'=>['id'=>'poplinks','class'=>'col-md-12 total-slider-orders margin-right-null padding-left-right-yes total-down-arrow']

                                   ],
        'dataProvider' => $customerDataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{items}",
        'itemView' => function ($model, $key, $index, $widget) {
          return $this->render('customer_list', [
            'model' => $model,
            'index' => $index+1,
          ]);
        },
    ]);
  ?>
  </tbody>
        </table>
        <div id="map"></div>
      </div>
    </div>
  </div>
</section>
<script>
  var map;
  var image = "./img/contact/location.png";
  var LatLng  = {lat: 41.1614954, lng: -81.2533256};
  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: LatLng,
      zoom: 12,
      disableDefaultUI: true,
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
<style>
  /*img {
    vertical-align: middle;
    border-style: none;
    width: 125px;
    height: 118px;
}
img {
    vertical-align: middle;
    border-style: none;
}*/
</style>