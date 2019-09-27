<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\models\Word;
use yii\helpers\ArrayHelper;
?>
<nav class="navbar navbar-default navbar-static-top m-b-0">
<div class="navbar-header">
                <div class="top-left-part">
				
				
				 
                    <!-- Logo -->
                    <a class="logo" href="index.html">
                        <!-- Logo icon image, you can use font-icon also -->
                        <!-- Logo text image you can use text also --> </a>
                </div>
                 <a style="color: white;margin-top: 10px;margin-right: 8px;display:inline-block;" class="navbar-right">
                       <?php if(Yii::$app->user->identity) echo ucfirst(Yii::$app->user->identity->username);?>

                  </a>
                <!-- /Logo -->
                <!-- Search input and Toggle icon -->


            </div>
 </nav>
