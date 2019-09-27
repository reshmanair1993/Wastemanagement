<?php
// use yii2assets\PrintThis;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;


$name = ucfirst($params['name']);
$subject = ucfirst($params['subject']);
$description = ucfirst($params['description']);
$amount = ucfirst($params['amount']);
$logoUrl = $params['logoUrl'];
// print_r($logoUrl);exit;
$lsgiAddress = $params['lsgiAddress'];
?>

<div class="container">
  <div class="memo-preview">
    <div class="logo-section">
      <div class=" row col-lg-12 col-md-12 col-sm-12 col-12 image-logo">
          <!-- <?php print_r($logoUrl);?> -->
            <img src="<?=$logoUrl;?>" />
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-12 col-12 cooperation-address">
        <h1><b><?php
            echo $lsgiAddress;
          ?></h1></b>
      </div>
      <div class="row col-lg-12 col-md-12 col-sm-12 col-12  field-padding subject">
        <h1><label for="charge">Invoice</label></h1>
      </div>
    </div>
    <div class="content-section">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <b><label for="charge">Date</label></b>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <?=date('Y-m-d H:i:s');?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <b><label for="charge">Subject</label></b>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <?=$subject?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <b><label for="charge">Description</label></b>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          <?=$description?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
        <b><label for="charge">Amount</label></b>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6  field-padding subject">
          ₹ <?=$amount?>/-
        </div>
      </div>
    </div>
  </div>
</div>
