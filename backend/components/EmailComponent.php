<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use frontend\models\TbMessage;
use frontend\models\TbPackage;
use backend\models\Person;
use jobs\models\TbFile;
class EmailComponent extends Component
{
  public $SENDGRID_API_KEY;
  function __construct($config=[]) {
      parent::__construct($config);

  }
  public function getList($name) {
    $url = "https://api.sendgrid.com/v3/contactdb/lists";
    $sendgrid = Yii::$app->sendGrid;
    $response = $this->curlGet($url,$headers);
  }
  public function addList($name) {
    $url = 'https://api.sendgrid.com/v3/contactdb/lists';
    $sendgrid = Yii::$app->sendGrid;
    $data = [
      'name' => $name
    ];
    $data = json_encode($data);
    $token = $this->SENDGRID_API_KEY;
    $headers = [
      "Authorization: Bearer $token"
    ];
    $response = $this->curlPost($url,$data,$headers);
    $response = json_decode($response);
    return $response;
  }
  public function addSubscriber($email) {
    $listName = isset(Yii::$app->params['subscriber-list-name'])?Yii::$app->params['subscriber-list-name']:'marketing';
    $this->addList($listName);
    $url = "https://api.sendgrid.com/v3/contactdb/recipients";
    $sendgrid = Yii::$app->sendGrid;
    $token = $this->SENDGRID_API_KEY;
    $data = [
      [
        'email' => $email
      ]
    ];
    $data =  json_encode($data);
    $headers = [
      "Authorization: Bearer $token"
    ];
    $response = $this->curlPost($url,$data,$headers);
    $params = [];
    $response = json_decode($response);
    return $response;
  }
  public function curlPost($url, $data=NULL, $headers = NULL) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if(!empty($data)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }

    curl_close($ch);
    return $response;
}

  public function curlGet($url, $headers = NULL) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }

    curl_close($ch);
    return $response;
}
public function sendEmail($modelMemo) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;
 $memoId = $modelMemo->id;
    // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'memoId' => $memoId,
    'amount' => $amount,
    'name'=>$name,
    'description' => $description
  ];
 $view = 'memo-send-mail';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
public function sendPaidEmail($modelMemo) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;
 // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'amount' => $amount,
    'name'=>$name,
    'description' => $description
  ];
 $view = 'memo-paid-send-mail';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
public function sendPaidInvoice($modelMemo,$modelLsgi) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;

 if($modelLsgi){
   $modelLogoImage        = $modelMemo->getImage($modelLsgi->image_id);
   if(isset($modelLogoImage)?$modelLogoImage:''){
     $url = $modelLogoImage->uri_full;
     $path =  Yii::$app->params['logo_image_base_url'];
     $logoUrl = $modelLogoImage->getFullUrl($url,$path);
    }
  }
  if($modelLsgi)
    $lsgiAddress = $modelLsgi->address;
 // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'amount' => $amount,
    'name'=>$name,
    'description' => $description,
    'logoUrl' => $logoUrl,
    'lsgiAddress' => $lsgiAddress
  ];
 $view = 'invoice';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
  public function sendPasswordReset($modelUser) {
    $modelPerson = $modelUser->person_id;
    if(!$modelPerson) return;
    $modelPerson = $this->findModelPerson($modelUser->person_id);
    $email = $modelPerson->email;
    $firstName = $modelPerson->first_name;
    $to = [
      'first-name' => $firstName,
      'name' => $firstName,
      'email' => $email,
    ];
    $from = [
      'email'=>'no-reply@smarttrivandrum.in'
    ];
    // $accountUrl = Yii::$app->frontendUtilities->getUrl('account');
    $params = [
      'heading'=>'Reset Your Password',
      'name'=>$firstName,
      'reset-token'=>$modelUser->password_reset_token,
      // 'accountUrl' => $accountUrl
    ];
    $view = 'password-reset-mail';
    $subject = 'Reset your password';
    $heading = 'Reset password';
    $message = '';
    $this->sendMail($from,$to,$subject,$message,$view,$params);
  }


  public function sendMail($from,$to,$subject,$message,$view='default-mail',$params =[],$ccs=[],$bccs=[]) {
  $sendGrid = Yii::$app->sendGrid;
  $fromEmail = $from['email'];
  $toEmail = $to['email'];

  $paramsTemp = [
   'from' => $from,
   'to' => $to,
   'subject' => $subject,
   'message' => $message
  ];
  $params = array_merge($params, $paramsTemp);
    $sendGrid->view->params['from'] = $from;
    $sendGrid->view->params['to'] = $to;
    $sendGrid->view->params['subject'] = $subject;
    foreach($params as $param => $val) {
      $sendGrid->view->params[$param] = $val;
    }

    $message = $sendGrid->compose($view,['params'=>$params]);
    $message->setFrom($fromEmail)->setTo($toEmail)->setSubject($subject);
    // print_r($message);exit;
    // foreach($ccs as $cc) {
    //   $message->setCc($cc);
    // }
    $message->getSendGridMessage()->setCcs($ccs);
    $message->getSendGridMessage()->setBccs($bccs);
   // $message->getSendGridMessage()->setAttachments($attach);
    // foreach($bccs as $bcc) {
    //   $message->setBcc($bcc);
    // }
    $message->send($sendGrid);
    foreach($sendGrid->view->params as $param => $val) { // otherwise headings may overlapp if two mails sent one after another in same method
      unset($sendGrid->view->params[$param]);
    }



 }
  protected function findModelPerson($personId)
  {
      $modelPerson = Person::find()->where(['status'=>1])->andWhere(['id' => $personId])->one();
        if($modelPerson)
      {
          return $modelPerson;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
  }
}
