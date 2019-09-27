<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\models\Subscriber;
use common\widgets\Alert;
use yii\web\view;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$modelSubscribe = new Subscriber;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <!-- <title><?= Html::encode($this->title) ?></title> -->
    <title>Green Trivandrum</title>
    <link rel="shortcut icon" href="<?=Url::to('@web/img/fav-icon.ico')?>" type="image/x-icon">
    <?php $this->head() ?>
</head>
<body>
    <style type="text/css">
        html{font-size: 16px;}
    </style>


    <!-- The Modal -->
    <div class="modal kitchen-modal" id="kitchenModal">
      <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close modal-close" data-dismiss="modal">
              <img src="<?=Url::to('@web/img/modal-close.png')?>">
            </button>
        
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Kitchen Bin Request</h4>

          </div>
          
          <!-- Modal body -->
          <div class="modal-body"> 
            <?php echo $this->render("/site/kitchen-bin-requests.php");?>
          </div>
          
        </div>
      </div>
    </div>

    <header>
      <div class="switch-field"> 
        <?php 
        $mlUrl = Yii::$app->urlManager->createUrl(['site','language'=>'ml']);
        $enUrl = Yii::$app->urlManager->createUrl(['site','language'=>'en']);
        ?>
        <ul class="">
          <li><a href="<?=$mlUrl?>">മലയാളം</a></li>
          <li class="active"><a href="<?=$enUrl?>">English</a></li>
        </ul>
      </div>
      <!--Translation tab-->
                <!-- <?php

                echo Html::beginForm(['/','language'=>1], 'GET', ['class' => 'form', 'id' => 'language-form']);
                ?>
                <div class="switch-field">
                  <?php
                    $language = ['ml' => 'മലയാളം', 'en' => 'English'];
                    $options['item'] = function ($index, $label, $name, $checked, $value) {
                    // if (null != (Yii::$app->request->get('language')) && $value == Yii::$app->request->get('language')) {
                    //   $checked = 'checked';
                    // }
                    // if (null == (Yii::$app->request->get('language')) && $value == 'en') {
                    //   $checked = 'checked';
                    // }
                    $return = '';
                    if($checked ) $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '">';
                    else $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" id="radio-'.$value.'" '.$checked.'>';
                    $return .= '<label for="radio-'. $value .'">'.  ucwords($label) . '</label>';
                    $return .= '';

                    return $return;
               };
                echo Html::radioList('language',null,$language,$options);
              ?></div><?php
                echo Html::endForm();

                ?> -->
                
      <!-- Menu starts here -->
      <div id="menu">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 pr-0">
            <ul>
              <li><a href="<?php echo Yii::$app->UrlManager->createUrl(["site"]); ?>">Home</a></li>
              <li><a href="<?=Url::to(['site/about'])?>">About</a></li>
              <li><a href="<?=Url::to(['site/activities'])?>">Activities</a></li>
              <li><a href="<?=Yii::$app->urlManager->createUrl(['site/contact-us']);?>">contact Us</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 p-0">
            <ul class="sm-br-0">
              <li><a href="<?=Url::to(['site/customer-portal'])?>">Customer portal</a></li>
              <li><a href="<?=Url::to(['site/vendor-portal'])?>">Vendor portal</a></li>
              <li><a href="<?=Url::to(['site/depatment-portal'])?>">Department portal</a></li>
              <li><a href="<?=Url::to(['site/garbage-monitoring'])?>">Garbage monitoring</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 p-0">
            <ul>
              <li><a href="<?=Url::to(['site/vehicle-tracking-system'])?>">Vehicle tracking</a></li>
              <li><a href="<?=Url::to(['site/news-and-events'])?>">News and events</a></li>
              <li><a href="<?=Yii::$app->urlManager->createUrl(['residential-association/index']);?>">Residential association</a></li>
              <li><a href="<?=Url::to(['site/departments'])?>">About Corporation</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 pr-0">
            <ul class="br-0">
              <li><a href="<?=Url::to(['site/centralized-facilities'])?>">Centralized facilities</a></li>
              <li><a href="<?=Url::to(['site/terms-and-policy'])?>">Terms and policy</a></li>
              <li><a href="<?=Url::to(['site/faq'])?>">Faq</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Menu ends here -->
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-4 col-4">
            <a href="<?php echo Yii::$app->UrlManager->createUrl(["site"]); ?>" class="logo">
              <img src="<?=Url::to('@web/img/big-size.png')?>" alt="Smart Trivandrum" class="header-logo">
              <img src="<?=Url::to('@web/img/nav-scroll-logo.png')?>" alt="Smart Trivandrum" class="logo-scroll">
            </a>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-8 col-8 ta-right">
            <div class="row">
              <div class="col-lg-9 col-md-8 col-sm-6 col-12 ">
                <!-- <div class="switch-field"> 
                  <?php 
                  $mlUrl = Yii::$app->urlManager->createUrl(['site','language'=>'ml']);
                  $enUrl = Yii::$app->urlManager->createUrl(['site','language'=>'en']);
                  ?>
                  <ul class="">
                    <li><a href="<?=$mlUrl?>">മലയാളം</a></li>
                    <li class="active"><a href="<?=$enUrl?>">English</a></li>
                  </ul>
                </div> -->
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 col-9 ta-right">
                <a href="#!" class="kit-hold" data-toggle="modal" data-target="#kitchenModal"><img src="<?=Url::to('@web/img/kitchen-bin.png')?>">
                  <h5>BIOCOMPOSER REGISTRATION</h5>
                </a>
              </div>
              <div class="col-lg-1 col-md-1 col-sm-2 col-3">
                <!--Toggle menu btn-->
                <div id="toggle">
                  <div class="one"></div>
                  <div class="two"></div>
                  <div class="three"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
<?php $this->beginBody() ?>
<?= $content ?>
<footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12 pr-30">
          <a href="<?php echo Yii::$app->UrlManager->createUrl(["site"]); ?>" class="footer-logo"><img src="<?=Url::to('@web/img/big-size.png')?>" alt=""></a>
          <h3>MY CITY BEAUTIFUL CITY</h3>
          <p>MY CITY BEAUTIFUL CITY</p>
          <?php $form = ActiveForm::begin(['id' => 'subscriber','action'=>Yii::$app->UrlManager->createUrl(["site/subscribe"])]);?>
            <div class="form-group">
               <?=$form->field($modelSubscribe, 'email')->textInput(['maxlength' => true,'class'=>'form-control','placeholder'=>"Enter your email"])->label(false);?>
              <button type="submit"><img src="<?=Url::to('@web/img/mail.png')?>" alt=""></button>
            </div>
   <?php ActiveForm::end();?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-10">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="<?php echo Yii::$app->UrlManager->createUrl(["site"]); ?>">Home</a></li>
                <li><a href="<?=Url::to(['site/about'])?>">About</a></li>
                <li><a href="<?=Url::to(['site/activities'])?>">Activities</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="<?=Url::to(['site/news-and-events'])?>">News</a></li>
                <li><a href="<?=Url::to(['site/contact-us'])?>">CONTACT US</a></li>
                <li><a href="<?=Url::to(['site/faq'])?>">FAQ</a></li>
              </ul>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="<?=Url::to(['site/terms-and-policy'])?>">PRIVACY & POLICY</a></li>
              </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 col-6 ta-right">
              <ul class="media-links">
                <li><a href="https://www.facebook.com/" target="_blank"><img src="<?=Url::to('@web/img/fb.png')?>" alt=""></a></li>
                <li><a href="https://www.instagram.com/" target="_blank"><img src="<?=Url::to('@web/img/insta.png')?>" alt=""></a></li>
                <li><a href="https://twitter.com/" target="_blank"><img src="<?=Url::to('@web/img/twitter.png')?>" alt=""></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <?php 
     $this->registerJs('
        var myVideo = document.getElementById("video");
        if(myVideo){
            function playPause() {
                if (myVideo.paused)
                  myVideo.play();
                else
                  myVideo.pause();
              }
        }

        jQuery(document).ready(function($){
        $("#language-form").on(\'click\', function(event){
             event.preventDefault();
             $("#language-form").submit();
             // window.location = "/?language="+en;
            });
        })
        ', View::POS_READY);
    ?>
  <div class="tag-line ta-center">
    <p>Copyright <?=date('Y')?>. All Right Reserved</p>
  </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
