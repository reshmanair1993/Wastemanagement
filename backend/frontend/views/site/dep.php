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
   <img src="<?=$modelDepartment->getProfileUrl()?>" alt="Departments" class="bg-img">
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
<section class="department-content">
  <div class="blue-bg"></div>
  <div class="container">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-12 col-12 left-img">
        <img src="./img/departments/dep-img1.jpg" alt="Departments">
      </div>
      <div class="col-lg-7 col-md-7 col-sm-12 col-12 bg-white">
        <h4>Lorem ipsum</h4>
        <h3>Lorem Ipsum</h3>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
          standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
          a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
          remaining essentially unchanged. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
          Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
          type and scrambled it to make a type </p>
      </div>
    </div>
    <div class="tile-img-holder">
      <img src="../img/departments/dep-img2.jpg" alt="Departments" class="img1">
      <img src="../img/departments/dep-img3.jpg" alt="Departments" class="img2">
    </div>
  </div>
</section>
<section class="dep-categories">
  <div class="container">
    <?php
      $deps = [];
      $ln_title = 'title_'.$language;
      $ln_description = 'description_'.$language;
      if ($modelDepartmentList->getCount() > 0) {
        $modelDepartments = $modelDepartmentList->getModels();
        $i = 0;
        foreach ($modelDepartments as $department) {
          $deps[$i]['title'] = $department->$ln_title;
          $deps[$i]['description'] = $department->$ln_description;
          $i += 1;
        }
        // print_r($deps);exit;
      }
      if (!empty($deps)) {
     ?>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <?php
        foreach ($deps as $key => $department):
          $class = '';
          if ($key == 0) {
            $class = 'active';
          }
      ?>
      <li class="nav-item">
        <a class="nav-link <?=$class?>" id="pills-revenue-tab" data-toggle="pill" href="#pills-revenue<?=$key?>" role="tab" aria-controls="pills-revenuw" aria-selected="true">
          <?=$department['title']?>
        </a>
      </li>
    <?php endforeach; ?>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <?php
        foreach ($deps as $key => $department):
          $class = '';
          if ($key == 0) {
            $class = 'active';
          }
      ?>
      <div class="tab-pane <?=$class?>" id="pills-revenue<?=$key?>" role="tabpanel" aria-labelledby="pills-revenue-tab">
        <div class="row">
          <?=$department['description']?>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  <?php } ?>
  </div>
</section>

<style >
  .tile-img-holder .img1 {
    position: absolute;
    right: 220px;
    width: 320px;
    height: 310px;
    object-fit: cover;
}
.tile-img-holder .img2 {
    position: absolute;
    right: 0;
    top: -41px;
    height: 310px;
    width: auto;
    object-fit: cover;
}
</style>
