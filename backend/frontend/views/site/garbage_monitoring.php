<?php
use frontend\models\Image;
use yii\helpers\Html;

$ln_title = 'title_'.$language;
$title = isset($modelGarbageMonitoring->$ln_title)?Html::encode($modelGarbageMonitoring->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelGarbageMonitoring->$ln_sub_title)?Html::encode($modelGarbageMonitoring->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelGarbageMonitoring->$ln_description)?$modelGarbageMonitoring->$ln_description:'';
?>
<section class="page-banner">
  <img src="<?=$modelGarbageMonitoring->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
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
  <?=$description?>
</section>