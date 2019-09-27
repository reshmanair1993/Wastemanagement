<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\controllers\DashboardController;
use yii\db\Query;
use yii\widgets\ActiveForm;
use backend\models\LoginForm;
use yii\web\View;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TbNewslettersubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$bannerImage = Yii::$app->homeUrl.'/img/banner.jpg';
?>

<div class="login-holder __web-inspector-hide-shortcut__">
	<div class="container">
		<div class="in-holder">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-6">
					<div class="welcome-wrapper">
						<h1>Welcome !</h1>
						
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-6">
					<div class="form-holder">
						<?php Pjax::begin(['timeout' => 5000 ,'enablePushState' => false,
											'id' =>'Pjax-login',]);?>
								<h2>Login</h2>
								<?php $form = ActiveForm::begin([
									'action' =>['account/login'],
									'options' => ['','data-pjax' => true,'class' => 'form-horizontal new-lg-form','enctype' => 'multipart/form-data','id'=>"loginform"]]);?>
									<div id="loginDiv">

								<form action="index.html" class="form-horizontal new-lg-form" id="loginform" name="loginform">
									<div class="form-group m-t-20">
										<div class="col-xs-12">
						   					<?= $form->field($modelLogin, 'username')->textInput(['maxlength' => true,'class'=>'form-control ','placeholder'=>'Username']) ?>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12">
										 <?= $form->field($modelLogin, 'password')->PasswordInput(['maxlength' => true,'class'=>'form-control ','placeholder'=>'Password'])?>
										</div>
									</div>
									<?php /*$form->field($modelLogin, 'reCaptcha')->widget(
									\himiklab\yii2\recaptcha\ReCaptcha::className(),
									['siteKey' => '6LcsiToUAAAAAIGfOjFqKqFOpm0udToyOYjq4y0U']) */?>

									<div class="form-group">
										<div class="col-md-12">
											<a class="text-dark pull-right" href="javascript:void(0)" id="to-recover"><i class="fa fa-lock m-r-5"></i> Forgot Password?</a>
										</div>
									</div>
									<div class="form-group text-center m-t-20">
										<div class="col-xs-12">
											<button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light-login" type="submit">LogIn</button>
										</div>
									</div>
									<div class="form-group m-b-0">
										<div class="col-sm-12 text-center">
											<!-- <p>Don't have an account? <a class="text-primary m-l-5" href="register.html"><b>Sign Up</b></a></p> -->
										</div>
									</div>
								</div>
								<?php ActiveForm::end(); ?>
								<?php $form = ActiveForm::begin([
									'action' =>['account/reset-password'],
									'options' => ['','data-pjax' => true,'class' => 'form-horizontal new-lg-form','enctype' => 'multipart/form-data','id'=>"loginform"]]);?>
									<div class="hidden" id="forgotpass">
										<div class="form-group">
											<div class="col-xs-12">
												<p class="text-muted">Enter your Username and instructions will be sent to you!</p>
											</div>
										</div>
										<div class="form-group">
											<div class="col-xs-12">
											  <?= $form->field($modelLogin, 'username')->textInput(['maxlength' => true,'class'=>'form-control ','placeholder'=>'Enter username'])->label('Username') ?>
											</div>
										</div>
										<div class="form-group text-center m-t-20">
											<div class="col-xs-12">
												<button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" id="to-login" type="submit">Reset</button>
											</div>
										</div>
									</div>
								<?php ActiveForm::end(); ?>
						</div>
						<?php
						if(isset($showSuccess) && $showSuccess) {
							$this->registerJs("
								swal('successfully send Reset mail ');

							",View::POS_END);
						}?>
					</div>
			</div>
		</div>
	</div>
</div>
<?= $this->registerJs("
$('#to-recover').click(function () {
	 $('#forgotpass').removeClass('hidden');
	 $('#loginDiv').addClass('hidden');
});
		",View::POS_END);?>

		<?= $this->registerJs("
		$('.waves-light-login').click(function () {
		 // $('#Pjax-login').on('pjax:end', function() {
			 //	 $.pjax.reload({container:'#Pjax-login'});
		 //  });

		});
				",View::POS_END);?>

<?php  Pjax::end();?>
