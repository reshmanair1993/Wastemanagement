<?php
use yii\helpers\Html;
use frontend\models\Image;

$ln_title = 'title_'.$language;
$title = isset($team->$ln_title)?Html::encode($team->$ln_title):'';
?>
<div class="col-lg-3 col-md-3 col-sm-6 col-12">
	<div class="prof-bg"></div>
	<img src="<?=$team->getProfileUrl()?>" alt="">
	<p><?=$title?></p>
</div>