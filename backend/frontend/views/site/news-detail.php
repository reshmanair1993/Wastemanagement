<?php
use yii\helpers\Html;
use frontend\models\Image;

$ln_title = 'title_'.$language;
$title = isset($modelNews->$ln_title)?Html::encode($modelNews->$ln_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelNews->$ln_description)?Html::encode($modelNews->$ln_description):'';
?>
<section class="page-banner">
  <img src="<?=Image::getImageUrlById($modelNews->featured_image_id)?>" alt="Smart Trivandrum" class="bg-img">
  <div class="container ta-center">
    <h1><?=$title?></h1>
    <div class="heading-underline">
      <span></span>
      <span class="white-bar"></span>
      <span></span>
    </div>
  </div>
</section>
<section class="content">
  <div class="container ta-center">
    <div class="content-box mtop">
      <p><?=$description?></p>
    </div>
  </div>
</section>