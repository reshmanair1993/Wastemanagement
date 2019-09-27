<?php
use frontend\models\Image;
use yii\helpers\Html;

$ln_title = 'title_'.$language;
$title = isset($modelActivity->$ln_title)?Html::encode($modelActivity->$ln_title):'';
$ln_sub_title = 'sub_title_'.$language;
$sub_title = isset($modelActivity->$ln_sub_title)?Html::encode($modelActivity->$ln_sub_title):'';
$ln_description = 'description_'.$language;
$description = isset($modelActivity->$ln_description)?$modelActivity->$ln_description:'';

$ln_title_sub = 'title_'.$language;
$title_sub = isset($modelActivitySub->$ln_title_sub)?Html::encode($modelActivitySub->$ln_title_sub):'';
$ln_sub_title_sub = 'sub_title_'.$language;
$sub_title_sub = isset($modelActivitySub->$ln_sub_title_sub)?Html::encode($modelActivitySub->$ln_sub_title_sub):'';
?>
<section class="page-banner">
  <img src="<?=$modelActivity->getProfileUrl()?>" alt="Smart Trivandrum" class="bg-img">
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
  if ($modelActivitiesList->getCount() > 0):
?>
<section class="activity-content">
  <div class="container">
    <h4><?=$title_sub?></h4>
    <h3><?=$sub_title_sub?></h3>
    <div class="row m-top">
      <?php
        $activities = $modelActivitiesList->getModels();
        foreach ($activities as $activity):
      ?>
      <div class="col-lg-4 col-md-4 col-sm-4 col-12 ta-center">
        <a class="box active" href="vehicle-tracking-system.php">
          <img src="<?=$activity->getProfileUrl()?>" alt="">
          <?php
            $ln_ac_title = 'title_'.$language;
            $ac_title = isset($activity->$ln_ac_title)?Html::encode($activity->$ln_ac_title):'';
            $ln_ac_sub_title = 'description_'.$language;
            $ac_sub_title = isset($activity->$ln_ac_sub_title)?Html::encode($activity->$ln_ac_sub_title):'';
          ?>
          <h4><?=$ac_title?></h4>
          <p><?=$ac_sub_title?></p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="activity-cont2">
  <div class="container">
    <?=$description?>
  </div>
</section>
<?php
  if ($modelProjectList->getCount() > 0):
?>
<section class="project-list">
  <div class="container">
    <div class="row">
      <?php
        $ln_proj_title = 'title_'.$language;
        $ln_proj_sub_title = 'sub_title_'.$language;
        $ln_proj_description = 'description_'.$language;

        $pm_title = isset($modelProject->$ln_proj_title)?Html::encode($modelProject->$ln_proj_title):'';
        $pm_sub_title = isset($modelProject->$ln_proj_sub_title)?Html::encode($modelProject->$ln_proj_sub_title):'';
        $pm_description = isset($modelProject->$ln_proj_description)?$modelProject->$ln_proj_description:'';
      ?>
      <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <h4><?=$pm_title?></h4>
        <h3><?=$pm_sub_title?></h3>
        <?=$pm_description?>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-12 col-12 overflow-hidden">
        <ul id="lightSlider">
          <?php
            foreach ($modelProjectList->getModels() as $project):
              $ln_pj_title = 'title_'.$language;
              $pj_title = isset($project->$ln_pj_title)?Html::encode($project->$ln_pj_title):'';

              $ln_pj_sub_title = 'sub_title_'.$language;
              $pj_sub_title = isset($project->$ln_pj_sub_title)?Html::encode($project->$ln_pj_sub_title):'';
          ?>
          <li><img src="<?=$project->getProfileUrl()?>"" /><p><strong><?=$pj_title?></strong><br/><?=$pj_sub_title?></p></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>