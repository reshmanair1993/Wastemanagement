<?php
use frontend\models\Image;
use yii\helpers\Html;
use yii\helpers\Url;

$ln_title = 'title_'.$language;
$title = isset($modelFaq->$ln_title)?Html::encode($modelFaq->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelFaq->$ln_sub_title)?Html::encode($modelFaq->$ln_sub_title):'';
?>
<section class="page-banner">
<img src="<?=Image::getImageUrlById($modelFaq->featured_image_id)?>" alt="Smart Trivandrum Banner" class="bg-img">
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
<section class="content faq-content">
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-12 col-12">
      <?php if ($modelFaqList->getCount() > 0): ?>
      <div id="accordion">
      	<?php
      	$i = 1;
      	foreach ($modelFaqList->getModels() as $faq):
      		$ln_title = 'title_'.$language;
      		$title = isset($faq->$ln_title)?Html::encode($faq->$ln_title):'';

      		$ln_description = 'description_'.$language;
      		$description = isset($faq->$ln_description)?Html::encode($faq->$ln_description):'';
      	?>
        <div class="card">
          <div class="card-header">
            <a class="panel-control" data-toggle="collapse" href="#collapse<?=$i?>">
              <img src="<?=Url::to('@web/img/faq/arrow-down.png')?>" alt="" class="closed">
              <img src="<?=Url::to('@web/img/faq/arrow-up.png')?>" alt="" class="open">
            </a>
            <a class="card-link" data-toggle="collapse" href="#collapse<?=$i?>">
              <?=$title?>
            </a>
          </div>
          <div id="collapse<?=$i?>" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <?=$description?>
            </div>
          </div>
        </div>
    	<?php $i = $i + 1; endforeach; ?>
      </div>
  	<?php endif; ?>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-12 col-12">
      <div class="form-holder mtop">
        <form action="#" class="quote-form">
          <h4>Lorem Ipsum is simply dummy text of.</h4>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your mail">
          </div>
          <div class="form-group">
            <textarea name="message" id="" cols="" rows="5" placeholder="Message" class="form-control"></textarea>
          </div>
          <button type="submit">Send</button>
        </form>
      </div>
    </div>
  </div>
</div>
</section>