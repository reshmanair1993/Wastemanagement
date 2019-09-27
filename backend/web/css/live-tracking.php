
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\controllers\DashboardController;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TbNewslettersubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="row bg-title">
 <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Dashboard 1</h4>
 </div>
       <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> <button class="right-side-toggle waves-effect waves-light btn-info btn-circle pull-right m-l-20"><i class="ti-settings text-white"></i></button>
                <a href="javascript: void(0);" target="_blank" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Buy Admin Now</a>
              <!--  <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Dashboard 1</li>
                </ol>-->
                <?php
		      // $breadcrumb[]  = ['label' => 'Home', 'url' => ''];
			     $this->title =  'Live Tracking ';
			     $breadcrumb[] = ['label' => $this->title, 'url' => '','template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
                <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
</div>

<div class="row __web-inspector-hide-shortcut__">
                  <div class="row">
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">NEW CLIENTS</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter">23</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">NEW CLIENTS</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter">23</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">NEW CLIENTS</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter">23</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">NEW CLIENTS</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter">23</span></li>
                                    </ul>
                                </div>
                            </div>
                      </div>






     </div>
