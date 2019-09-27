
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
      <?=$description?>
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