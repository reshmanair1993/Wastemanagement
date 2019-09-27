<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=Yii::$app->homeUrl;?>favicon.ico">
    <title>Waste Management</title>
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>
<body >
<div class="preloader" style="width:100%; height:100%;">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
</div>
  <link href="http://cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.min.css"
      rel="stylesheet">

  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"   rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css" rel="stylesheet">
<?php $this->beginBody() ?>

   <?=$content?>

<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>
<!--<script src="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBaIO280orMJjXQ6iE8inx-QlOWzcu4ld8"></script>-->
