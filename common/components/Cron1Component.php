<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\ScheduleWard;
use backend\models\ScheduleTest;
use backend\models\Ward;
use backend\models\Lsgi;
use backend\models\AccountService;
use backend\models\LsgiServiceFee;
use backend\models\GreenActionUnit;
use backend\models\PushMessage;
use backend\models\Service;
class Cron1Component extends Component
{

    public function scheduleServiceRequests($lsgiId,$hksId,$date){
      // $modelWard = Ward::find()->where(['id'=>$wardId])->andWhere(['status'=>1])->one();
      $dateNext = date('Y-m-d', strtotime($date));
      $dayofweek = date('w', strtotime($dateNext)) + 1;
      $dayofDate = date('d', strtotime($dateNext));
      $scheduleIds = [];
      $modelSchedule = ScheduleTest::find()
      ->leftJoin('schedule_customer_test','schedule_customer_test.schedule_id=schedule_test.id')
       ->where(['schedule_test.green_action_unit_id'=>$hksId])
      ->andWhere(['schedule_customer_test.status'=>1])
      ->andWhere(['schedule_test.status'=>1])
      ->andWhere(['>','schedule_test.service_id',0])
      ->groupBy(['id'])
      ->all();
      $resultCount =  sizeof($modelSchedule);

        echo "LSGI ID: $lsgiId - HKS ID : $hksId - DATE : $date - $resultCount  schedules found<br />";
      foreach ($modelSchedule as $key => $value) {
        if($value->type==1)
        {
          if($value->week_day ==$dayofweek)
          {
            $scheduleIds[] = $value->id;
          }
        }elseif($value->type==2)
        {
          if($value->month_day ==$dayofDate)
          {
            $scheduleIds[] = $value->id;
          }

        }
        elseif($value->type==3)
        {
          if($value->date ==$dateNext)
          {
            $scheduleIds[] = $value->id;
          }

        }

      }
      $scheduleIds = array_unique($scheduleIds);
     
      /*
            Code for view
            CREATE view pending_service_request as select sr.service_id,sr.account_id_customer,sr.requested_datetime,sr.remarks,sr.is_cancelled,sr.ward_id,sr.lsgi_id,sa.servicing_status_option_id,sa.account_id_gt,sa.remarks as gt_remarks,sa.quantity,sa.quality,sa.planned_date FROM service_request sr LEFT JOIN service_assignment sa ON sr.id = sa.service_request_id WHERE sr.status = 1 AND (sr.is_cancelled IS NULL OR sr.is_cancelled =  0) AND (sa.servicing_status_option_id IS NULL OR (sa.servicing_status_option_id IS NOT NULL AND sa.status = 0))
      */

      foreach ($scheduleIds as $key => $scheduleId) {
      
        $modelSchedule = ScheduleTest::find()->where(['id'=>$scheduleId])->andWhere(['status'=>1])->one();
         $status = 1;
         $lsgi = $modelSchedule->lsgi_id;
         $serviceId = $modelSchedule->service_id;
echo "<br />service id = $serviceId<br />";
         $wardId = $modelSchedule->ward_id;
         $scheduleId = $modelSchedule->id;
         echo "<br />$lsgiId - $wardId - $serviceId ";
         $dateToday = date('Y-m-d');
          $qry =  "select account_id_customer FROM schedule_customer_test where schedule_id = :schedule_id and status = 1 and account_id_customer NOT IN (SELECT account_id_customer FROM service_request_test sr INNER JOIN service_assignment_test sa ON sa.service_request_id = sr.id and sr.status =1 and sa.status = 1 and sa.servicing_status_option_id IS NOT NULL and sa.planned_date = :date AND service_id = :service_id) and account_id_customer NOT IN ( SELECT account_id_customer FROM pending_service_request_test where service_id = :service_id) ";
          $command =  Yii::$app->db->createCommand($qry);
         $command->bindParam(":schedule_id",$scheduleId);
         $command->bindParam(":service_id",$serviceId); 
         $command->bindParam(":date",$date); 
         echo $command->getRawSql();
//exit;
           $results = $command->queryAll();
             
$count =  sizeof($results); 
$i = 0;
$queries = [];
while($i<$count) {
    $accountId = $results[$i]['account_id_customer'];
    $queries[] = "($accountId,$serviceId,$wardId,'$dateToday','$dateToday','$dateToday',$lsgiId,1)";
    $accountIdCustomer[] =$accountId; 
    $i++;
}
echo "<br />Count is $count <br />";

if($count) {
$queries = implode(",",$queries);
 
 // $qry = "INSERT IGNORE  INTO service_request_test(account_id_customer,service_id,ward_id,created_at,modified_at,requested_datetime,lsgi_id,status)  
 //     VALUES $queries

 //  ";
       
// echo $qry  ;  
// $command =  Yii::$app->db->createCommand($qry);
// $command->execute();
$key = 'account_id';
$value = $modelSchedule->account_id_gt;
$modelPushMessage = new PushMessage;
$modelPushMessage->account_id = $value;
$serviceData = Service::find()->where(['id'=>$serviceId])->andWhere(['status'=>1])->one();
$serviceName = isset($serviceData)?$serviceData->name:'';
$wardData = Ward::find()->where(['status'=>1])->andWhere(['id'=>$wardId])->one();
$wardName = isset($wardData)?$wardData->name:'';
$modelPushMessage->message = 'Service scheduled successfully. Service is '. $serviceName. 'Ward : '.$wardName;
$modelPushMessage->save(false);
$result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);
}
         $lsgiId = $lsgiId;
        // $this->getSubsciptions($lsgiId);
      $this->scheduleRequestGt($modelSchedule->account_id_gt,$date);

      }
}
public function getServiceId($name) {
  // print_r($name);exit;
  $query = "select * from camera_service where name = '$name'";
  if(!$query){
    $qry =  "INSERT into camera_service(name) values ($name)";
    $camera_service =  Yii::$app->db->createCommand($qry);
    $query = "select * from camera_service where name = '$name'";
  }
  $cameraServices = Yii::$app->db->createCommand($query)->queryAll();
  return $cameraServices;
}
public function heartbeatDelay(){
  $intervalTime = 5 * 60;
  $serviceName = "Camera faulty";
  $cameraServices = $this->getServiceId($serviceName);
  // $qry = "select * from camera_heartbeat where ((TIMESTAMPDIFF(SECOND,previous_entry_created_at,created_at))>$intervalTime) and status = 1 and processed = 0";
  // $modelHeartbeats = Yii::$app->db->createCommand($qry)->queryAll();
  // $ids = [];
  // foreach ($modelHeartbeats as $modelHeartbeat) {
  //   $ids[] = $modelHeartbeat['id'];
  // }
  // $ids = join(', ', $ids);


/****************************/
  //view Query
  // select `csr`.`id` AS `request_id`,csr.camera_id,`csr`.`service_id` AS `service_id`,`csa`.`id` AS `assignment_id`,`csa`.`camera_servicing_status_option_id` AS `status_option_id`,`csr`.`request_date` AS `request_date`,`csa`.`date` AS `assignment_date`,`csa`.`account_id_technician` AS `account_id_technician`,`csr`.`notification_sent` AS `notification_sent` from (`wms`.`camera_service_request` `csr` left join `wms`.`camera_service_assignment` `csa` on((`csr`.`id` = `csa`.`camera_service_request_id`))) where ((`csr`.`status` = 1) and ((`csa`.`status` = 1) or isnull(`csa`.`status`)))
/***************************/

  $query = "INSERT into camera_service_request(camera_id,service_id,request_date,notification_sent,heartbeat_id)  select camera_id,:serviceId ,:requestDate , :notificationSent,id from camera_heartbeat where ((TIMESTAMPDIFF(SECOND,previous_entry_created_at,created_at))>$intervalTime) and status = 1 and processed = 0 and (camera_id,:serviceId ) NOT IN (SELECT camera_id,service_id FROM `camera_service_details` WHERE status_option_id is null)";
  $command =  Yii::$app->db->createCommand($query);
  $dateToday = date('Y-m-d H:i:s');
  $notification_sent = 0;
  $command->bindParam(':serviceId',$cameraServices[0]['id']);
  $command->bindParam(':requestDate',$dateToday);
  $command->bindParam(':notificationSent',$notification_sent);
  $command->execute();
  $processedQuery = "UPDATE camera_heartbeat SET processed = 1 WHERE id in (select heartbeat_id from camera_service_details where status_option_id is null )";
  $processed = Yii::$app->db->createCommand($processedQuery)->execute();

}

public function ServiceRequestNotification(){
  $message = "Camera Failed";
  // print_r($message);exit;
  $query = "select * from camera_service_request where notification_sent = 0 and status = 1";
  $cameraServiceRequests = Yii::$app->db->createCommand($query)->queryAll();
  $ids = [];
  foreach ($cameraServiceRequests as $cameraServiceRequest) {
    $ids[] = $cameraServiceRequest['camera_id'];
  }
  $modelIncident = new Incident;
  foreach ($ids as $id) {
  $modelTokens = $modelIncident->getToken($id);
  }

  $authKey = Yii::$app->params['authKey'];
  $service = new FirebaseNotifications(['authKey' => $authKey]);
  $groups = [];
  foreach ($ids as $id) {
    $groupNames = $modelIncident->getGroup($id);
    // print_r($id);exit;
  }
  foreach ($groupNames as $groupName) {

    $group= str_replace(' ', '', $groupName->name);
    $groups[]=$group;
    $modelUsers = $modelIncident->getUsers($groupName->id);

    foreach ($modelUsers as $modelUser) {
      $tokens = $modelUser->token;
      $service->addToTopic($tokens,$group);
    }
    if($modelUsers) {
        $service->sendCameraNotificationToTopic($message ,$group,$id);
      }
    }
  // }
    foreach ($cameraServiceRequests as $cameraServiceRequest) {
      $notificationQuery = "UPDATE camera_service_request SET notification_sent = 1";
      $notification = Yii::$app->db->createCommand($notificationQuery)->execute();
    }
}

public function scheduleRequestGt($gt,$plannedDate)
    {
        $qry = "INSERT INTO service_assignment_test(account_id_gt,service_request_id,created_at,modified_at,planned_date)
         SELECT :gt as account_id_gt,service_request_test.id as service_request_id,:dateToday as created_at, 
         :dateToday as modified_at, :planned_date as planned_date from service_request_test  left join schedule_customer_test on schedule_customer_test.account_id_customer=service_request_test.account_id_customer left join service_assignment_test on service_assignment_test.service_request_id=service_request_test.id where service_assignment_test.service_request_id is null and service_request_test.status = 1  and schedule_customer_test.status = 1 and (service_assignment_test.status=1 or service_assignment_test.status is null) group by service_request_test.id";
         $dateToday = date('Y-m-d H:i:s');
         $command =  Yii::$app->db->createCommand($qry);
         $command->bindParam(':gt',$gt);
         // $command->bindParam(':wardId',$wardId);
         $command->bindParam(':dateToday',$dateToday);
         $command->bindParam(':planned_date',$plannedDate);
         $command->execute();
      // $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
      // $days = $modelLsgi->service_assigment_expiry_hours/24;
      // if($days>0)
      // {
      //   $date = date('Y-m-d H:i:s');
      //   $expiry =  date('Y-m-d H:i:s',; strtotime($date. '$days days'));
      //   $qry = "INSERT INTO service_assignment(account_id_gt,service_request_id,created_at,modified_at,planned_date,expiry_ts) SELECT :gt as account_id_gt,service_request.id as service_request_id,:dateToday as created_at, :dateToday as modified_at, :dateToday as planned_date,:expiry as expiry_ts from service_request  left join schedule_customer on schedule_customer.account_id_customer=service_request.account_id_customer left join service_assignment on service_assignment.service_request_id=service_request.id where service_assignment.service_request_id is null and service_request.status = 1  and schedule_customer.status = 1 and (service_assignment.status=1 or service_assignment.status is null) group by service_request.id";
      //    $dateToday = date('Y-m-d H:i:s');
      //    $command =  Yii::$app->db->createCommand($qry);
      //    $command->bindParam(':gt',$gt);
      //    $command->bindParam(':expiry',$expiry);
      //    $command->bindParam(':dateToday',$dateToday);
      //    $command->execute();
      // }
      // else
      // {
      //   $qry = "INSERT INTO service_assignment(account_id_gt,service_request_id,created_at,modified_at,planned_date) SELECT :gt as account_id_gt,service_request.id as service_request_id,:dateToday as created_at, :dateToday as modified_at, :dateToday as planned_date from service_request  left join schedule_customer on schedule_customer.account_id_customer=service_request.account_id_customer left join service_assignment on service_assignment.service_request_id=service_request.id where service_assignment.service_request_id is null and service_request.status = 1  and schedule_customer.status = 1 and (service_assignment.status=1 or service_assignment.status is null) group by service_request.id";
      //    $dateToday = date('Y-m-d H:i:s');
      //    $command =  Yii::$app->db->createCommand($qry);
      //    $command->bindParam(':gt',$gt);
      //    // $command->bindParam(':wardId',$wardId);
      //    $command->bindParam(':dateToday',$dateToday);
      //    $command->execute();
      // }
    }

public function scheduleServiceRequestsWards($date){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
      foreach ($modelLsgi as $lsgiData) {
        $modelHks = GreenActionUnit::find()->where(['lsgi_id'=>$lsgiData->id])->andWhere(['status'=>1])->all();
        foreach ($modelHks as $hksData) {
          $lsgiId = $lsgiData->id;
          $hksId = $hksData->id;
          $this->scheduleServiceRequests($lsgiId,$hksId,$date);

        } 
    }
  }

  public function getSubsciptions($lsgiId){
         $qry = "INSERT INTO payment_request(amount,account_id_customer,service_id,created_at,modified_at,requested_date,is_subscription_payment)SELECT lsgi_service_fee.amount AS amount, account_service.account_id AS account_id, account_service.service_id AS service_id,:dateToday as created_at, :dateToday as modified_at, :dateToday as requested_date,:is_subscription_payment as is_subscription_payment FROM account_service LEFT JOIN lsgi_service_fee ON lsgi_service_fee.service_id=account_service.service_id WHERE (lsgi_service_fee.status=1) AND (account_service.status=1) AND (lsgi_service_fee.lsgi_id=:lsgiId) AND (lsgi_service_fee.payment_collection_type=1)

         and  NOT EXISTS(SELECT amount,account_id_customer,service_id,created_at,modified_at,requested_date FROM payment_request WHERE payment_request.status=:status)
         "
         ;

         $command =  Yii::$app->db->createCommand($qry);
         $status = 1;
         $lsgiId = $lsgiId;
         $dateToday = date('Y-m-d H:i:s');
         $is_subscription_payment = 1;
         $command->bindParam(':status',$status);
         $command->bindParam(':dateToday',$dateToday);
         $command->bindParam(':is_subscription_payment',$is_subscription_payment);
         $command->bindParam(':lsgiId',$lsgiId);
         $command->execute();
}

public function schedulePaymentLsgi(){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
      foreach ($modelLsgi as $lsgiData) {
      $lsgiId = $lsgiData->id;
      $this->getSubsciptions($lsgiId);
      // INSERT INTO payment_request(amount,account_id_customer,service_id)SELECT `lsgi_service_fee`.`amount` AS `amount`, `account_service`.`account_id` AS `account_id`, `account_service`.`service_id` AS `service_id` FROM `account_service` LEFT JOIN `lsgi_service_fee` ON lsgi_service_fee.service_id=account_service.service_id WHERE (`lsgi_service_fee`.`status`=1) AND (`account_service`.`status`=1) AND (`lsgi_service_fee`.`lsgi_id`=14) AND (`lsgi_service_fee`.`payment_collection_type`=1)
    }
  }
}
