<?php
use frontend\models\Image;
use yii\helpers\Html;

$ln_title = 'title_'.$language;
$title = isset($modelDepartment->$ln_title)?Html::encode($modelDepartment->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelDepartment->$ln_sub_title)?Html::encode($modelDepartment->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelDepartment->$ln_description)?$modelDepartment->$ln_description:'';
?>
<section class="page-banner">
  <img src="<?=$modelDepartment->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
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
<section class="cont1 dept-portal">
  <div class="container ta-left">
    <div class="row">
      <?=$description?>
      <a class="bt-primary" href="<?=Yii::$app->params['backend_public_url']?>">Login</a>
    </div>
  </div>
  </div>
</section>


    </div>
  </div>
</section>
<style>
  /*h3 {
    font-weight: 700;
    color: #736b6b;
    font-size: 32px;
    margin-left: 135px;
}*/
.ta-center img {
  margin-top: 30px;
}
.ta-center h3 {
  color: #000 !important;
}
</style>