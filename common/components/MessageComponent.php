<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\User;
use backend\models\Account;
use backend\models\AuthAssociation;

class MessageComponent extends Component
{
    public function sendMessage($key,$value,$message){
      $result = Yii::$app->onesignal->notifications->add([
        'contents' => ["en" => $message],
        'filters' => [
                 [
                     'field' => 'tag',
                     'key' => $key,
                     'value' => $value
                 ]
        ],
        // 'included_segments'=>['All']
        'include_player_ids' => [],
    ]);
      return $result;
    }

public function sendSMS($authKey,$senderId,$countryCode,$phone,$message) {
$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms?country=91",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => "{ \"sender\": \"GRNTVM\", \"route\": \"4\", \"country\": \"$countryCode\", \"sms\": [ { \"message\": \"$message\", \"to\": [ \"$phone\" ] } ] }",
CURLOPT_SSL_VERIFYHOST => 0,
CURLOPT_SSL_VERIFYPEER => 0,
CURLOPT_HTTPHEADER => array(
"authkey: $authKey",
"content-type: application/json"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
// echo "cURL Error #:" . $err;
} else {
// echo $response;
}

}
   
}
