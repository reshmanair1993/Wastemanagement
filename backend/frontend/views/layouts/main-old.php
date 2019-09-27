<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\web\view;
use yii\helpers\Url;

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
    <title><?= Html::encode($this->title) ?></title>
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
              <img src="<?=Url::to('@web')?>/img/modal-close.png">
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
      <!--Translation tab-->
                <?php

                echo Html::beginForm(['/'], 'GET', ['class' => 'form', 'id' => 'language-form']);
                ?>
                <div class="switch-field">
                  <?php
                    $language = ['ml' => 'Malayalam', 'en' => 'English'];
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

                ?>
      <!-- Menu starts here -->
      <div id="menu">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 pr-0">
            <ul>
              <li><a href="<?=Url::base()?>">Home</a></li>
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
          <div class="col-lg-8 col-md-8 col-sm-8 col-6">
            <a href="<?=Url::base()?>" class="logo"><img src="<?=Url::to('@web')?>/img/portal.png" alt="Smart Trivandrum"></a>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-6 ta-right">
            <div class="row">
              <div class="col-lg-9 col-md-9 col-sm-9 col-7 ta-right">
                <a href="#!" class="kit-hold" data-toggle="modal" data-target="#kitchenModal"><img src="<?=Url::to('@web')?>/img/kitchen-bin.png">
                  <h5>BIOCOMPOSER REGISTRATION</h5>
                </a>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3 col-5">
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
          <a href="./" class="footer-logo"><img src="<?=Url::to('@web')?>/img/portal.png" alt=""></a>
          <h3>Loremipsumdolorsitamet,consectetu</h3>
          <p>Lorem Ipsum is simply dummy text of.</p>
          <form action="#">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Enter your email">
              <button type="submit"><img src="../img/mail.png" alt=""></button>
            </div>
          </form>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-10">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="./">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="activities.php">Activities</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="news-and-events.php">News</a></li>
                <li><a href="contact-us.php">CONTACT US</a></li>
                <li><a href="faq.php">FAQ</a></li>
              </ul>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
              <ul>
                <li><a href="privacy-and-policy.php">PRIVACY & POLICY</a></li>
              </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 col-6 ta-right">
              <ul class="media-links">
                <li><a href="#"><img src="../img/fb.png" alt=""></a></li>
                <li><a href="#"><img src="../img/insta.png" alt=""></a></li>
                <li><a href="#"><img src="../img/twitter.png" alt=""></a></li>
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
<style>
  .logo img {
    width: 180px;
    height: 68px;
    object-fit: contain;
}
</style>
