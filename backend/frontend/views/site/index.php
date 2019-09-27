<?php

use yii\helpers\Url;
/* @var $this yii\web\View */
$ln_title = json_decode($modelCmsHome->title);
$title = isset($ln_title->$language)?$ln_title->$language:'';

$ln_sub_title = json_decode($modelCmsHome->sub_title);
$sub_title = isset($ln_sub_title->$language)?$ln_sub_title->$language:'';

$ln_top_box_one_title = json_decode($modelCmsHome->top_box_one_title);
$top_box_one_title = isset($ln_top_box_one_title->$language)?$ln_top_box_one_title->$language:'';

$ln_top_box_two_title = json_decode($modelCmsHome->top_box_two_title);
$top_box_two_title = isset($ln_top_box_two_title->$language)?$ln_top_box_two_title->$language:'';

$ln_top_box_three_title = json_decode($modelCmsHome->top_box_three_title);
$top_box_three_title = isset($ln_top_box_three_title->$language)?$ln_top_box_three_title->$language:'';

$ln_top_box_one_sub = json_decode($modelCmsHome->top_box_one_sub);
$top_box_one_sub = isset($ln_top_box_one_sub->$language)?$ln_top_box_one_sub->$language:'';

$ln_top_box_two_sub = json_decode($modelCmsHome->top_box_two_sub);
$top_box_two_sub = isset($ln_top_box_two_sub->$language)?$ln_top_box_two_sub->$language:'';

$ln_top_box_three_sub = json_decode($modelCmsHome->top_box_three_sub);
$top_box_three_sub = isset($ln_top_box_three_sub->$language)?$ln_top_box_three_sub->$language:'';

$ln_abt_head_one = json_decode($modelCmsHome->abt_head_one);
$abt_head_one = isset($ln_abt_head_one->$language)?$ln_abt_head_one->$language:'';

$ln_abt_head_two = json_decode($modelCmsHome->abt_head_two);
$abt_head_two = isset($ln_abt_head_two->$language)?$ln_abt_head_two->$language:'';

$ln_abt_head_three = json_decode($modelCmsHome->abt_head_three);
$abt_head_three = isset($ln_abt_head_three->$language)?$ln_abt_head_three->$language:'';

$ln_abt_head_four = json_decode($modelCmsHome->abt_head_four);
$abt_head_four = isset($ln_abt_head_four->$language)?$ln_abt_head_four->$language:'';

$ln_mid_four_title = json_decode($modelCmsHome->mid_four_title);
$mid_four_title = isset($ln_mid_four_title->$language)?$ln_mid_four_title->$language:'';
$ln_mid_four_sub_title = json_decode($modelCmsHome->mid_four_sub_title);
$mid_four_sub_title = isset($ln_mid_four_sub_title->$language)?$ln_mid_four_sub_title->$language:'';

$ln_mid_four_one_title = json_decode($modelCmsHome->mid_four_one_title);
$mid_four_one_title = isset($ln_mid_four_one_title->$language)?$ln_mid_four_one_title->$language:'';
$ln_mid_four_one_sub_title = json_decode($modelCmsHome->mid_four_one_sub_title);
$mid_four_one_sub_title = isset($ln_mid_four_one_sub_title->$language)?$ln_mid_four_one_sub_title->$language:'';

$ln_mid_four_two_title = json_decode($modelCmsHome->mid_four_two_title);
$mid_four_two_title = isset($ln_mid_four_two_title->$language)?$ln_mid_four_two_title->$language:'';
$ln_mid_four_two_sub_title = json_decode($modelCmsHome->mid_four_two_sub_title);
$mid_four_two_sub_title = isset($ln_mid_four_two_sub_title->$language)?$ln_mid_four_two_sub_title->$language:'';

$ln_mid_four_three_title = json_decode($modelCmsHome->mid_four_three_title);
$mid_four_three_title = isset($ln_mid_four_three_title->$language)?$ln_mid_four_three_title->$language:'';
$ln_mid_four_three_sub_title = json_decode($modelCmsHome->mid_four_three_sub_title);
$mid_four_three_sub_title = isset($ln_mid_four_three_sub_title->$language)?$ln_mid_four_three_sub_title->$language:'';

$ln_mid_four_four_title = json_decode($modelCmsHome->mid_four_four_title);
$mid_four_four_title = isset($ln_mid_four_four_title->$language)?$ln_mid_four_four_title->$language:'';
$ln_mid_four_four_sub_title = json_decode($modelCmsHome->mid_four_four_sub_title);
$mid_four_four_sub_title = isset($ln_mid_four_four_sub_title->$language)?$ln_mid_four_four_sub_title->$language:'';

$ln_video_title = json_decode($modelCmsHome->video_title);
$video_title = isset($ln_video_title->$language)?$ln_video_title->$language:'';
$ln_video_sub_title = json_decode($modelCmsHome->video_sub_title);
$video_sub_title = isset($ln_video_sub_title->$language)?$ln_video_sub_title->$language:'';

$ln_circle_menu_one = json_decode($modelCmsHome->circle_menu_one);
$circle_menu_one = isset($ln_circle_menu_one->$language)?$ln_circle_menu_one->$language:'';

$ln_circle_menu_two = json_decode($modelCmsHome->circle_menu_two);
$circle_menu_two = isset($ln_circle_menu_two->$language)?$ln_circle_menu_two->$language:'';

$ln_circle_menu_three = json_decode($modelCmsHome->circle_menu_three);
$circle_menu_three = isset($ln_circle_menu_three->$language)?$ln_circle_menu_three->$language:'';

$ln_circle_menu_four = json_decode($modelCmsHome->circle_menu_four);
$circle_menu_four = isset($ln_circle_menu_four->$language)?$ln_circle_menu_four->$language:'';

$this->title = 'Smart Trivandrum';
?>
<!-- Banner section starts here -->
  <section class="banner">
    <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_banner)?>" alt="Smart Trivandrum Banner" class="bg-img">
    <div class="container ta-center">
      <h1><?=$title?></h1>
      <h4><?=$sub_title?></h4>
      <div class="heading-underline">
        <span></span>
        <span class="white-bar"></span>
        <span></span>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <a class="box" href="smart-survey.php">
            <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_top_box_one)?>" alt="smart-survey">
            <h4><?=$top_box_one_title?></h4>
            <p><?=$top_box_one_sub?></p>
          </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <a class="box" href="monitoring-system.php">
            <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_top_box_two)?>" alt="">
            <h4><?=$top_box_two_title?></h4>
            <p><?=$top_box_two_sub?></p>
          </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <a class="box" href="vehicle-tracking-system.php">
            <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_top_box_three)?>" alt="Smart vehicle tracking">
            <h4><?=$top_box_three_title?></h4>
            <p><?=$top_box_three_sub?></p>
          </a>
        </div>
      </div>
    </div>
  </section>
<!-- Banner section ends here -->

<!-- About section starts here -->
<section class="about-section">
  <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_abt)?>" alt="About us">
  <div class="container ta-center">
    <h4><?=$abt_head_one?></h4>
    <h3><?=$abt_head_two?></h3>
    <p><?=$abt_head_three?></p>
  </div>
</section>
<!-- About section ends here -->

<!-- content section starts here -->
<section class="blue-banner">
  <div class="container ta-center">
    <p><?=$abt_head_four?></p>
    <div class="heading-underline">
      <span></span>
      <span class="white-bar"></span>
      <span></span>
    </div>
  </div>
</section>
<!-- content section ends here -->

<!-- content section starts here -->
<section class="content-wrapper box-section">
  <div class="container">
    <h4><?=$mid_four_title?></h4>
    <h3><?=$mid_four_sub_title?></h3>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a class="box ta-center" href="customer-portal.php">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_mid_four_one)?>" alt="Customer portal">
          <h4><?=$mid_four_one_title?></h4>
          <p><?=$mid_four_one_sub_title?></p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a class="box ta-center" href="vendor-portal.php">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_mid_four_two)?>" alt="Vendor portal">
          <h4><?=$mid_four_two_title?></h4>
          <p><?=$mid_four_two_sub_title?></p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a class="box ta-center" href="activities.php">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_mid_four_three)?>" alt="Activities portal">
          <h4><?=$mid_four_three_title?></h4>
          <p><?=$mid_four_three_sub_title?></p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a class="box ta-center" href="departments.php">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_mid_four_four)?>" alt="Department portal">
          <h4><?=$mid_four_four_title?></h4>
          <p><?=$mid_four_four_sub_title?></p>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- content section ends here -->

<!-- Video section starts here -->
<section class="video-wrapper">
  <video width="100%" height="100%" controls autoplay class="bg-img" id="video">
    <source src="<?=$modelCmsHome->getVideoUrl()?>" type="video/mp4">
  </video>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <h4><?=$video_title?></h4>
        <h3><?=$video_sub_title?></h3>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 ta-right">
        <button onclick="playPause()"><img src="<?=Url::to('@web/img/play.png')?>" alt=""></button>
      </div>
    </div>
  </div>
</section>
<!-- Video section ends here -->

<!-- content section starts here -->
<section class="round-menu">
  <div class="container ta-center">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-6">
        <a href="centralized-facilities.php" class="round-box">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_circle_menu_one)?>" alt="">
        </a>
        <a href="centralized-facilities.php"><?=$circle_menu_one?></a>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-6">
        <a href="news-and-events.php" class="round-box">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_circle_menu_two)?>" alt="">
        </a>
        <a href="news-and-events.php"><?=$circle_menu_two?></a>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-6">
        <a href="residential-association.php" class="round-box">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_circle_menu_three)?>" alt="">
        </a>
        <a href="residential-association.php"><?=$circle_menu_three?></a>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-6">
        <a href="contact-us.php" class="round-box">
          <img src="<?=$modelCmsHome->getImageUrlById($modelCmsHome->fk_image_circle_menu_four)?>" alt="">
        </a>
        <a href="contact-us.php"><?=$circle_menu_four?></a>
      </div>
    </div>
  </div>
</section>
<!-- content section ends here -->

<script>
  var myVideo = document.getElementById("video");

  function playPause() {
  	alert();
    if (myVideo.paused)
      myVideo.play();
    else
      myVideo.pause();
  }
</script>