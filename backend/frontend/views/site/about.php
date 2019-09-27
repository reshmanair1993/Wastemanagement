<?php
use frontend\models\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$ln_title = 'title_'.$language;
$title = isset($modelAbout->$ln_title)?Html::encode($modelAbout->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelAbout->$ln_sub_title)?Html::encode($modelAbout->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelAbout->$ln_description)?$modelAbout->$ln_description:'';

$ln_team_title = 'title_'.$language;
$team_title = isset($modelTeam->$ln_team_title)?Html::encode($modelTeam->$ln_team_title):'';
$ln_team_sub_title = 'sub_title_'.$language;
$team_sub_title = isset($modelTeam->$ln_team_sub_title)?Html::encode($modelTeam->$ln_team_sub_title):'';
$ln_team_description = 'description_'.$language;
$team_description = isset($modelTeam->$ln_team_description)?Html::encode($modelTeam->$ln_team_description):'';

$ln_partner_title = 'title_'.$language;
$partner_title = isset($modelPartner->$ln_partner_title)?Html::encode($modelPartner->$ln_partner_title):'';
$ln_partner_sub_title = 'sub_title_'.$language;
$partner_sub_title = isset($modelPartner->$ln_partner_sub_title)?Html::encode($modelPartner->$ln_partner_sub_title):'';


?>
<section class="page-banner">
  <img src="<?=$modelAbout->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
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
<section class="cont1 about-content">
  <div class="container ta-left">
    <div class="row">
      <?=$description?>
    </div>
  </div>
</section>
<section class="featured-content ta-center">
  <img src="<?=Url::to('@web/img/about/banner.jpg')?>" alt="" class="bg-img">
  <div class="container">
    <h4><?=$team_title?></h4>
    <h3><?=$team_sub_title?></h3>
    <p><?=$team_description?></p>
    <div class="row profile">
      <?php
        if ($modelTeamListDataProvider->getCount() > 0) {
          $teamList = $modelTeamListDataProvider->getModels();
          foreach ($teamList as $team) {
            echo $this->render('_about-team', [
              'team' => $team,
              'language' => $language
            ]);
          }
        }
      ?>
    </div>
    <div class="heading-underline">
      <span></span>
      <span class="white-bar"></span>
      <span></span>
    </div>
  </div>
</section>
<section class="partners">
  <div class="container ta-center">
    <h4><?=$partner_title?></h4>
    <h3><?=$partner_sub_title?></h3>
    <div class="row">
      <?php
        if ($modelPartnersListDataProvider->getCount() > 0) {
          $partnerList = $modelPartnersListDataProvider->getModels();
          foreach ($partnerList as $partner) {
            echo $this->render('_about-partner', [
              'partner' => $partner,
              'language' => $language
            ]);
          }
        }
      ?>
    </div>
  </div>
</section>