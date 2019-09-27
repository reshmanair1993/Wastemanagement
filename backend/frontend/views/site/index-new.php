<?php

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Image;

$ln_title = 'title_'.$language;
$title = isset($modelHome->$ln_title)?Html::encode($modelHome->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelHome->$ln_sub_title)?Html::encode($modelHome->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$homeDescription = isset($modelHome->$ln_description)?$modelHome->$ln_description:'';

$this->title = 'Smart Trivandrum';
?>
<!-- Banner section starts here -->
  <section class="banner">
    <!-- <img src="<?=Image::getImageUrlById($modelHome->featured_image_id)?>" alt="Smart Trivandrum" class="bg-img"> -->
     <img src="<?=$modelHome->getProfileUrl()?>" / class="bg-img">
    <div class="container ta-center">
      <h1><?=$title?></h1>
      <h4><?=$sub_title?></h4>
      <div class="heading-underline">
        <span></span>
        <span class="white-bar"></span>
        <span></span>
      </div>
      <div class="row">
        <?php
          if ($modelFacilitiesList->getCount() > 0):
            foreach ($modelFacilitiesList->getModels() as $facility):
              $ln_title = 'title_'.$language;
              $title = isset($facility->$ln_title)?Html::encode($facility->$ln_title):'';
              $ln_description = 'description_'.$language;
              $description = isset($facility->$ln_description)?Html::encode($facility->$ln_description):'';

              $ln_sub_title = 'sub_title_'.$language;
              $url = isset($facility->$ln_sub_title)?Html::encode($facility->$ln_sub_title):'';
        ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <a class="box" href="<?=$url?>">
            <!-- <img src="<?=Image::getImageUrlById($facility->featured_image_id)?>" alt=""> -->
            <img src="<?=$facility->getProfileUrl()?>" />
            <h4><?=$title?></h4>
            <p><?=substr($description, 0, 75)?></p>
          </a>
        </div>
        <?php
            endforeach;
          endif;
        ?>
      </div>
    </div>
  </section>
<!-- Banner section ends here -->
<?=$homeDescription?>
<?php
  if (null != $modelHomeLinksOne):
    $ln_titlePOne = 'title_'.$language;
    $titlePOne = isset($modelHomeLinksOne->$ln_titlePOne)?Html::encode($modelHomeLinksOne->$ln_titlePOne):'';
    $ln_sub_titlePOne = 'sub_title_'.$language;
    $sub_titlePOne = isset($modelHomeLinksOne->$ln_sub_titlePOne)?Html::encode($modelHomeLinksOne->$ln_sub_titlePOne):'';
?>
<!-- content section starts here -->
<section class="content-wrapper box-section">
  <div class="container">
    <h4><?=$sub_titlePOne?></h4>
    <h3><?=$titlePOne?></h3>
    <div class="row">
    <?php
      if ($modelHomeMenuOne->getCount() > 0) {
        foreach ($modelHomeMenuOne->getModels() as $menu) {
          $ln_title = 'title_'.$language;
          $title = isset($menu->$ln_title)?Html::encode($menu->$ln_title):'';
          $ln_description = 'description_'.$language;
          $description = isset($menu->$ln_description)?Html::encode($menu->$ln_description):'';

          $ln_sub_title = 'sub_title_'.$language;
          $url = isset($menu->$ln_sub_title)?Html::encode($menu->$ln_sub_title):'';
    ?>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a class="box ta-center" href="<?=$url?>">
          <img src="<?=$menu->getProfileUrl()?>" alt="Customer portal">
          <h4><?=$title?></h4>
          <p><?=substr($description, 0, 75)?></p>
        </a>
      </div>
    <?php
        }
      }
    ?>
    </div>
  </div>
</section>
<!-- content section ends here -->
<?php endif; ?>
<?php
  if (null != $modelVideo):
    $ln_titleVid = 'title_'.$language;
    $titleVid = isset($modelVideo->$ln_titleVid)?Html::encode($modelVideo->$ln_titleVid):'';
    $ln_sub_titleVid = 'sub_title_'.$language;
    $sub_titleVid = isset($modelVideo->$ln_sub_titleVid)?Html::encode($modelVideo->$ln_sub_titlePOne):'';
    $ln_descriptionVid = 'description_'.$language;
    $descriptionVid = isset($modelVideo->$ln_descriptionVid)?Html::encode($modelVideo->$ln_descriptionVid):'';
?>
<!-- Video section starts here -->
<section class="video-wrapper">
  <video width="100%" height="100%" controls autoplay class="bg-img" id="video">
    <source src="<?=$descriptionVid?>" type="video/mp4">
  </video>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <h4><?=$titleVid?></h4>
        <h3><?=$sub_titleVid?></h3>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 ta-right">
        <button onclick="playPause()"><img src="<?=Url::to('@web/img/play.png')?>" alt=""></button>
      </div>
    </div>
  </div>
</section>
<!-- Video section ends here -->
<?php endif; ?>
<?php
  if (null != $modelHomeLinksTwo) {
    $twoDescription = 'description_'.$language;
    echo isset($modelHomeLinksTwo->$twoDescription)?$modelHomeLinksTwo->$twoDescription:'';
  }
?>
