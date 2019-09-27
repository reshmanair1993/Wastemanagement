<?php
use frontend\models\Image;
use yii\helpers\Html;

$ln_title = 'title_'.$language;
$title = isset($modelFacilitiesPage->$ln_title)?Html::encode($modelFacilitiesPage->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelFacilitiesPage->$ln_sub_title)?Html::encode($modelFacilitiesPage->$ln_sub_title):'';
?>
<section class="page-banner">
  <img src="<?=$modelFacilitiesPage->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
  <div class="container ta-center">
    <h1><?=$title?></h1>
    <h4><?=$sub_title?></h4>
    <div class="heading-underline">
      <span></span>
      <span class="white-bar"></span>
      <span></span>
    </div>
  </div>
</section>
<?php
  if ($modelFacilitiesList->getCount() > 0):
?>
<section class="cont1 centralized-content">
  <div class="container">
    <?php

    $facilities = $modelFacilitiesList->getModels();
    $i = 0;
    foreach ($facilities as $facility):
      $ln_title = 'title_'.$language;
      $title = isset($facility->$ln_title)?Html::encode($facility->$ln_title):'';
      $ln_sub_title = 'sub_title_'.$language;
      $sub_title = isset($facility->$ln_sub_title)?Html::encode($facility->$ln_sub_title):'';
      $ln_description = 'description_'.$language;
      $description = isset($facility->$ln_description)?$facility->$ln_description:'';
      if (($i % 2) == 0){
    ?>
    <div class="row">
      <div class="col-lg-7 col-md-8 col-sm-7 col-12 ta-left">
        <h4><?=$sub_title?></h4>
        <h3><?=$title?></h3>
        <p><?=$description?></p>
      </div>
      <div class="col-lg-5 col-md-4 col-sm-5 col-3  ta-right">
        <img src="<?=$facility->getProfileUrl()?>" alt="">
      </div>
    </div>
    <?php
    } else{
    ?>
    <div class="row">
      <div class="col-lg-5 col-md-4 col-sm-5 col-12 ta-left">
        <img src="<?=$facility->getProfileUrl()?>" alt="">
      </div>
      <div class="col-lg-7 col-md-8 col-sm-7 col-12  ta-right">
        <h4><?=$sub_title?></h4>
        <h3><?=$title?></h3>
        <p><?=$description?></p>
      </div>
    </div>
    <?php
      }
    ?>
    <?php $i = $i + 1; endforeach; ?>
  </div>
</section>
<?php endif; ?>