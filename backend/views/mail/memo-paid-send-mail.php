<?php
 use yii\helpers\Url;
 // $token = $params['reset-token'];
 // $activationUrl =  Url::toRoute(['account/reset-password','token'=>$token],'http' );
 $name = ucfirst($params['name']);
 $subject = ucfirst($params['subject']);
 $description = ucfirst($params['description']);
 $amount = ucfirst($params['amount']);
?>
<p style="color:#9c9c9c;font-size:16px;">
  Subject : <?=$subject?>
</p>
<p>
  <?=$description?>
</p>
<!-- <a href="<?php //echo $activationUrl?>" target="_blank" style="margin:45px 0;outline:0;display:block;width:200px;margin-left:50px;background:#FF7043;color:#fff;font-size:18px;text-decoration:none;padding:13px 16px;border-radius:5px;text-align:center;"> -->
You have successfully Paid the amount <?=$amount?>'/-'
<!-- </a> -->
<!-- <p style="color:#9c9c9c;font-size:16px;"> -->
  <!-- or you can copy the below link in your browser -->
<!-- </p> -->
 <!-- <?php //echo $activationUrl; ?> -->
