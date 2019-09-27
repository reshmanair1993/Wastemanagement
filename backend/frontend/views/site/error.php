<?php
use yii\helpers\Url;
?>
<section class="page-banner">
    <img src="<?=Url::to('@web/img/technopark.jpg')?>" alt="Smart Trivandrum" class="bg-img">
    <div class="container ta-center">
      <h1>Some error occured...!</h1>
    </div>
  </section>
  <section class="content">
    <div class="container ta-center">
      <div class="content-box mtop">
        <p><?=$exception->getMessage();?></p>
      </div>
    </div>
  </section>