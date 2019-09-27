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
    <title>Hadiyya</title>
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
      </script>

<script>
$('#publish-updates').click(function() {
	var url = "<?= Url::to(['site/publish-updates']);?>";
	$.post(url,function(response) {
		alert('All updates have been published');
	})
});
</script>
   <script type="text/javascript">
          $(document).ready(function ($) {
              // delegate calls to data-toggle="lightbox"
              $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function (event) {
                  event.preventDefault();
                  return $(this).ekkoLightbox({
                      onShown: function () {
                          if (window.console) {
                              return console.log('Checking our the events huh?');
                          }
                      }
                      , onNavigate: function (direction, itemIndex) {
                          if (window.console) {
                              return console.log('Navigating ' + direction + '. Current item: ' + itemIndex);
                          }
                      }
                  });
              });
              //Programatically call
              $('#open-image').click(function (e) {
                  e.preventDefault();
                  $(this).ekkoLightbox();
              });
              $('#open-youtube').click(function (e) {
                  e.preventDefault();
                  $(this).ekkoLightbox();
              });
              // navigateTo
              $(document).delegate('*[data-gallery="navigateTo"]', 'click', function (event) {
                  event.preventDefault();
                  var lb;
                  return $(this).ekkoLightbox({
                      onShown: function () {
                          lb = this;
                          $(lb.modal_content).on('click', '.modal-footer a', function (e) {
                              e.preventDefault();
                              lb.navigateTo(2);
                          });
                      }
                  });
              });
          });
      </script>
