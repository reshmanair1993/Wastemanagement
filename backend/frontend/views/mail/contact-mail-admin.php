<?php
 use yii\helpers\Url;
 // $token = $params['reset-token'];
 // $activationUrl =  Url::toRoute(['account/reset-password','token'=>$token],'http' );
 $name = ucfirst($params['name']);
 $phone = ucfirst($params['phone']);
 $email = ucfirst($params['email']);
 $subject = ucfirst($params['subject']);
?>
<p style=" color:#9c9c9c;font-size:16px;">
      Reply back as early as possible to maintain and improve relationship with the customer
    </p>
    <div style="margin-bottom:50px;border:1px solid #f1f1f1;background:#fff;border-radius:5px;padding-bottom:25px">
      <p style="margin:20px 25px 0 25px;color:#9c9c9c;font-size:18px;">Name</p>
      <p style="margin:10px 25px 0 25px;color:#555;font-size:18px;"><?=$name?></p>
      <p style="margin:25px 25px 0 25px;color:#9c9c9c;font-size:18px;">Email</p>
      <p style="margin:10px 25px 0 25px;color:#555;font-size:18px;"><?=$email?></p>
      <p style="margin:25px 25px 0 25px;color:#9c9c9c;font-size:18px;">Phone</p>
      <p style="margin:10px 25px 0 25px;color:#555;font-size:18px;"><?=$phone?></p>
      <p style="margin:25px 25px 0 25px;color:#9c9c9c;font-size:18px;">Message</p>
      <p style="margin:10px 25px 0 25px;color:#555;font-size:18px;"><?=$message?></p>
    </div>
