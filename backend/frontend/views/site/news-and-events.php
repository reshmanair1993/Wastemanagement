<?php
use frontend\models\Image;
use yii\helpers\Html;
use yii\helpers\Url;

$ln_title = 'title_'.$language;
$title = isset($modelNewsAndEvents->$ln_title)?Html::encode($modelNewsAndEvents->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelNewsAndEvents->$ln_sub_title)?Html::encode($modelNewsAndEvents->$ln_sub_title):'';
?>
<section class="page-banner">
<img src="<?=$modelNewsAndEvents->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
	<!-- <img src="<?=Image::getImageUrlById($modelNewsAndEvents->featured_image_id)?>" alt="Smart Trivandrum" class="bg-img"> -->
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
	if ($modelNews->getCount() > 0):
	?>
	<section class="news-cont1">
	<div class="container ta-left">
	  <?php
	  	$lnnews_title = 'title_'.$language;
	  	$news_title = isset($modelNewsPage->$lnnews_title)?Html::encode($modelNewsPage->$lnnews_title):'';
	  ?>
	  <h4 class="section-head ta-center"><?=$news_title?></h4>
	  <div class="row">
	  	<?php
	  		$news = $modelNews->getModels();
	  		$limit = 6;
	  		$arr = [];
	  		$size = sizeof($news);
			$arr = [];
			$val = 0;
			for ($i=0; $i < $size; $i++) { 
				if ($val == 0) {
					$arr[$i] = 0;
					$val = $val + 1;
				}
				elseif ($val == 1) {
					$arr[$i] = 1;
					$val = $val + 1;
				}
				elseif ($val == 2) {
					$arr[$i] = 1;
					$val = $val + 1;
				}
				elseif ($val == 3) {
					$arr[$i] = 0;
					$val = 0;
				}
				
			}
			// print_r($arr);
	  		$limit = sizeof($news)>=$limit?6:sizeof($news);
	  		for ($i=0; $i < $limit; $i++):
	  			$newsSingle = $news[$i];
	  			$ln_news_title = 'title_'.$language;
	  			$newsTitle = isset($newsSingle->$ln_news_title)?Html::encode($newsSingle->$ln_news_title):'';
	  			$ln_news_description = 'description_'.$language;
	  			$newsDescription = isset($newsSingle->$ln_news_description)?Html::encode($newsSingle->$ln_news_description):'';
	  			$widthClass = $arr[$i]==0?'col-lg-7 col-md-7 col-sm-12 col-12':'col-lg-5 col-md-5 col-sm-12 col-12';
	  			$descLength = $arr[$i]==0?150:50;
	  			
	  	?>
	    <div class="<?=$widthClass?>">
	  	<a href="<?=Url::to(['site/news', 'slug' => $newsSingle->slug])?>">
	      <div class="img-box">
	        <img src="<?=Image::getImageUrlById($newsSingle->featured_image_id)?>" alt="news">
	        <h4 class="news-title"><?=$newsTitle?></h4>
	        <p class="news-content"><?=substr($newsDescription, 0, $descLength)?></p>
	      </div>
		</a>
	    </div>
		<?php endfor; ?>
	  </div>
	</div>
	</section>
	<?php endif; ?>
	<?php
	if ($modelEvents->getCount() > 0):
	?>
	<section class="news-cont2">
	<div class="container">
	  <?php
	  	$lnevents_title = 'title_'.$language;
	  	$events_title = isset($modelEventsPage->$lnevents_title)?Html::encode($modelEventsPage->$lnevents_title):'';
	  ?>
	  <h4 class="section-head ta-center"><?=$events_title?></h4>
	  <?php
  		$events = $modelEvents->getModels();
  		$limit = 6;
  		$limit = sizeof($events)>=$limit?6:sizeof($events);
  		for ($i=0; $i < $limit; $i++):
  			$eventsSingle = $events[$i];
  			$ln_events_title = 'title_'.$language;
  			$eventsTitle = isset($eventsSingle->$ln_events_title)?Html::encode($eventsSingle->$ln_events_title):'';
  			$ln_events_description = 'description_'.$language;
  			$newsDescription = isset($eventsSingle->$ln_events_description)?Html::encode($eventsSingle->$ln_events_description):'';
  	  ?>
	  <div class="event-box">
	    <div class="row">
	      <div class="col-lg-4 col-md-4 col-sm-12 col-12">
	        <div class="img-holder">
	          <div class="publish-date">sep<span>02</span></div>
	          <img src="<?=Image::getImageUrlById($eventsSingle->featured_image_id)?>" alt="Events">
	          <div class="text">
	            <h4><?=$eventsTitle?></h4>
	            <p><?=substr($newsDescription, 0, 50)?></p>
	          </div>
	        </div>
	      </div>
	      <div class="col-lg-8 col-md-8 col-sm-12 col-12">
	        <div class="row">
	          <div class="col-lg-8 col-md-8 col-sm-8 col-12">
	            <div class="event-desc">
	              <h4><?=$eventsTitle?></h4>
	              <p><?=substr($newsDescription, 0, 100)?></p>
	              <ul>
	                <li>When?</li>
	                <li>12.08.2018</li>
	              </ul>
	              <ul>
	                <li>where?</li>
	                <li>Trivandrum,kovalam</li>
	              </ul>
	            </div>
	          </div>
	          <div class="col-lg-4 col-md-4 col-sm-4 col-12 ta-center">
	            <h5>Lorem ipsum</h5>
	            <a href="#" class="bt bt-primary">Lorem</a>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	<?php endfor; ?>
	</div>
</section>
<?php endif; ?>