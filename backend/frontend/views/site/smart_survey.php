<?php
use frontend\models\Image;
use yii\helpers\Html;

$ln_title = 'title_'.$language;
$title = isset($modelSmartSurvey->$ln_title)?Html::encode($modelSmartSurvey->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelSmartSurvey->$ln_sub_title)?Html::encode($modelSmartSurvey->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelSmartSurvey->$ln_description)?$modelSmartSurvey->$ln_description:'';
?>
<section class="page-banner">
  <img src="<?=Image::getImageUrlById($modelSmartSurvey->featured_image_id)?>" alt="Smart Trivandrum" class="bg-img">
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
<section class="cont1">
  <div class="container ta-left">
    <div class="row">
      <?=$description?>
    </div>
  </div>
</section>