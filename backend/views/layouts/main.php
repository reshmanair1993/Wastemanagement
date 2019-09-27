<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
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
    <title>Wastemanagement System</title>
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>
<body >
<?php $this->beginBody() ?>
 <div id="wrapper">
    <?php include "top-bar.php"; ?>
<?php include "sidebar.php"; ?>
<div id="page-wrapper">

            <div class="container-fluid">
              <?=$content?>
            </div>
<div id="myDiv"></div>
  <footer class="footer text-center"> <?php include 'footer.php';?> </footer>
</div>

</div>





<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>

    <script src="https://apis.google.com/js/api:client.js"></script>
<script type="text/javascript">
         (function () {
                 [].slice.call(document.querySelectorAll('.sttabs')).forEach(function (el) {
                 new CBPFWTabs(el);
             });
         })();
     </script>

   <script>
        var BaseurlAll = '<?php echo Url::base()?>';
        $(document).ready(function(){
            $(".menu-toggle").click(function(){
                $(".sidebar").toggleClass("active");
            })
        })
      </script>
