<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\ScheduleWard;
use backend\models\Schedule;
use backend\models\Ward;
use backend\models\Lsgi;
use backend\models\Service;
use backend\models\AccountService;
use backend\models\LsgiServiceFee;
use backend\models\GreenActionUnit;
use backend\models\GreenActionUnitWard;
use backend\models\EvaluationConfigCompletionTime;
use backend\models\EvaluationConfigComplaintsCount;
use backend\models\EvaluationConfigCompletionPercentage;
use backend\models\PushMessage;
use backend\models\Backup;
class CronComponent extends Component
{

     
public function scheduleBackup()
    {
      // $lastDate = '2019-01-01';
      $modelBackup = Backup::find()->orderby('id DESC')->one();
      if($modelBackup)
      {
        $lastDate  = $modelBackup->last_backup_date;
      }
       // $lastDate = date('Y-m-d',strtotime($lastDate));
       $today = date('Y-m-d');
       $today = strtotime($today);
       $lastDate = strtotime($lastDate);
       if(($today - $lastDate)/60/60/24>=7){
        $DBUSER="troisDba";
        $DBPASSWD="coCsZTMl0YiztoI9";
        $DATABASE="waste_management";
        $host="localhost";

        $filename = "backup-" . date("d-m-Y-H-i-s") . ".sql";
        $path  = Yii::$app->params['back_up_url'];
        $file_path = $path.$filename;
        $full_path = Yii::getAlias($file_path);
        exec("mysqldump --user={$DBUSER} --password={$DBPASSWD} --host={$host} {$DATABASE} --result-file={$full_path} 2>&1", $output);
        var_dump($output);
        $modelBackup = new Backup;
        $modelBackup->last_backup_date = date('Y-m-d');
        $modelBackup->save(false);
      }
    }  

   
public function scheduleRequestGt($gt,$plannedDate) {
        $qry = "INSERT INTO service_assignment(account_id_gt,service_request_id,created_at,modified_at,planned_date)
         SELECT :gt as account_id_gt,service_request.id as service_request_id,:dateToday as created_at, 
         :dateToday as modified_at, :planned_date as planned_date from service_request  left join schedule_customer on schedule_customer.account_id_customer=service_request.account_id_customer left join service_assignment on service_assignment.service_request_id=service_request.id where service_assignment.service_request_id is null and service_request.status = 1  and schedule_customer.status = 1 and (service_assignment.status=1 or service_assignment.status is null) group by service_request.id";
         $dateToday = date('Y-m-d H:i:s');
         $command =  Yii::$app->db->createCommand($qry);
         $command->bindParam(':gt',$gt); 
         $command->bindParam(':dateToday',$dateToday);
         $command->bindParam(':planned_date',$plannedDate);
         $command->execute();
      
}
public function scheduleServiceRequests($lsgiId,$hksId,$date){ 
      $dateNext = date('Y-m-d', strtotime($date));
      $dayofweek = date('w', strtotime($dateNext)) + 1;
      $dayofDate = date('d', strtotime($dateNext));
      $scheduleIds = [];
      $modelSchedule = Schedule::find()
      ->leftJoin('schedule_customer','schedule_customer.schedule_id=schedule.id')
       ->where(['schedule.green_action_unit_id'=>$hksId])
      ->andWhere(['schedule_customer.status'=>1])
      ->andWhere(['schedule.status'=>1])
      ->andWhere(['>','schedule.service_id',0])
      ->groupBy(['id'])
      ->all();
      $resultCount =  sizeof($modelSchedule);
      echo "LSGI ID: $lsgiId - HKS ID : $hksId - DATE : $date - $resultCount  schedules found<br />";
      foreach ($modelSchedule as $key => $value) {
        if($value->is_non_residential==0){
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
           elseif($value->type==4)
        {
            $scheduleIds[] = $value->id;
        }
        elseif($value->type==5)
        {
          if($value->week_day ==$dayofweek)
          {
          $scheduleDate = date('Y-m-d',strtotime($value->created_at));
          $datediff = $dateNext - $scheduleDate;
          $diff = round($datediff / (60 * 60 * 24));
          if($diff % 14 ==0)
          {
            $scheduleIds[] = $value->id;
          }
        }
      }
      }
    elseif($value->is_non_residential==1)
    {

      if($value->type==2)
        {
          if($value->week_day ==$dayofweek)
          {
            $scheduleIds[] = $value->id;
          }
        }elseif($value->type==4)
        {
          if($value->month_day ==$dayofDate)
          {
            $scheduleIds[] = $value->id;
          }

        }
        elseif($value->type==5)
        {
          if($value->date ==$dateNext)
          {
            $scheduleIds[] = $value->id;
          }

        }
        elseif($value->type==1)
        {
            $scheduleIds[] = $value->id;
        }
        elseif($value->type==3)
        {
          if($value->week_day ==$dayofweek)
          {
          $scheduleDate = date('Y-m-d',strtotime($value->created_at));
          $datediff = $dateNext - $scheduleDate;
          $diff = round($datediff / (60 * 60 * 24));
          if($diff % 14 ==0)
          {
            $scheduleIds[] = $value->id;
          }
        }
      }

    }

      }
      $scheduleIds = array_unique($scheduleIds);
     
      /*
            Code for view
            CREATE view pending_service_request as select sr.service_id,sr.account_id_customer,sr.requested_datetime,sr.remarks,sr.is_cancelled,sr.ward_id,sr.lsgi_id,sa.servicing_status_option_id,sa.account_id_gt,sa.remarks as gt_remarks,sa.quantity,sa.quality,sa.planned_date FROM service_request sr LEFT JOIN service_assignment sa ON sr.id = sa.service_request_id WHERE sr.status = 1 AND (sr.is_cancelled IS NULL OR sr.is_cancelled =  0) AND (sa.servicing_status_option_id IS NULL OR (sa.servicing_status_option_id IS NOT NULL AND sa.status = 0))
      */

      foreach ($scheduleIds as $key => $scheduleId) {
      
        $modelSchedule = Schedule::find()->where(['id'=>$scheduleId])->andWhere(['status'=>1])->one();
         $status = 1;
         $lsgi = $modelSchedule->lsgi_id;
         $serviceId = $modelSchedule->service_id;
echo "<br />service id = $serviceId<br />";
         $wardId = $modelSchedule->ward_id;
         $scheduleId = $modelSchedule->id;
         echo "<br />$lsgiId - $wardId - $serviceId ";
         $dateToday = date('Y-m-d H:i:s');
          $qry =  "select account_id_customer FROM schedule_customer where schedule_id = :schedule_id and status = 1 and account_id_customer NOT IN (SELECT account_id_customer FROM service_request sr INNER JOIN service_assignment sa ON sa.service_request_id = sr.id and sr.status =1 and sa.status = 1 and sa.servicing_status_option_id IS NOT NULL and sa.planned_date = :date AND service_id = :service_id) and account_id_customer NOT IN ( SELECT account_id_customer FROM pending_service_request where service_id = :service_id) ";
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
    // $accountIdCustomer[] =$accountId; 
    $i++;
}
echo "<br />Count is $count <br />";

if($count) {
$queries = implode(",",$queries);
 
 $qry = "INSERT IGNORE  INTO service_request(account_id_customer,service_id,ward_id,created_at,modified_at,requested_datetime,lsgi_id,status)  
     VALUES $queries

  ";
       
echo $qry  ;  
$command =  Yii::$app->db->createCommand($qry);
$command->execute();
// $key = 'account_id';
// $customerIds = rtrim($customerIds, ',');;
// $value = $modelSchedule->account_id_gt;
// $modelPushMessage = new PushMessage;
// $modelPushMessage->account_id = $value;
// $serviceData = Service::find()->where(['id'=>$serviceId])->andWhere(['status'=>1])->one();
// $serviceName = isset($serviceData)?$serviceData->name:'';
// $wardData = Ward::find()->where(['status'=>1])->andWhere(['id'=>$wardId])->one();
// $wardName = isset($wardData)?$wardData->name:'';
// $modelPushMessage->message = 'Service scheduled successfully. Service is '. $serviceName. 'Ward : '.$wardName.'Total customers : '.$count.'Date : '.$dateToday;
// $modelPushMessage->save(false);
// $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);

// $modelPushMessageNew = new PushMessage;
// $modelPushMessageNew->account_id = $customerIds;
// $modelPushMessageNew->message = 'Service scheduled successfully. Service is'. $serviceName. 'Ward : '.$wardName;
// $modelPushMessageNew->save(false);

// $result = Yii::$app->message->sendMessage($key,$customerIds,$modelPushMessageNew->message);  

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

//   public function getSubsciptions($lsgiId){
//          $qry = "INSERT INTO payment_request(amount,account_id_customer,service_id,created_at,modified_at,requested_date,is_subscription_payment) SELECT lsgi_service_fee.amount AS amount, account_service.account_id AS account_id, account_service.package_id AS service_id,:dateToday as created_at, :dateToday as modified_at, :dateToday as requested_date,:is_subscription_payment as is_subscription_payment FROM account_service LEFT JOIN lsgi_service_fee ON lsgi_service_fee.service_id=account_service.package_id LEFT JOIN account on account.id=account_service.account_id  LEFT JOIN lsgi on lsgi_service_fee.lsgi_id=lsgi.id LEFT JOIN ward on ward.lsgi_id=lsgi.id LEFT JOIN customer on customer.ward_id= ward.id left join payment_request on payment_request.account_id_customer=account.id
//           WHERE (lsgi_service_fee.status=1) AND (account_service.status=1) AND (lsgi_service_fee.lsgi_id=:lsgiId) AND (lsgi_service_fee.payment_collection_type=1) and customer.status=1 and account.status=1 and lsgi.status=1 and ward.status=1
//           and (payment_request.account_id_customer,DATE(payment_request.requested_date)) NOT IN (select pr.account_id_customer,date(:dateToday) FROM payment_request pr where status = 1 and pr.is_subscription_payment=1 and pr.is_closed=0)
//           group by account_service.account_id,account_service.package_id
//          "
//          ;

//          $command =  Yii::$app->db->createCommand($qry);
//          $status = 1;
//          $lsgiId = $lsgiId;
//          $dateToday = date('Y-m-d H:i:s');
//          $is_subscription_payment = 1;
//          $command->bindParam(':status',$status);
//          $command->bindParam(':dateToday',$dateToday);
//          $command->bindParam(':is_subscription_payment',$is_subscription_payment);
//          $command->bindParam(':lsgiId',$lsgiId);
//          $command->execute();

//          $qry2 = 'UPDATE lsgi set subscription_fee_collection_date=:dateToday where id=:lsgiId';
        
//          $command2 =  Yii::$app->db->createCommand($qry2);
         
//          $lsgiId = $lsgiId;
//          $dateToday = date('Y-m-d H:i:s');
//          $dateNew = date('Y-m-d H:i:s');
//          $date =  date('Y-m-d H:i:s', strtotime($dateNew. ' +30 days'));
//          $command2->bindParam(':dateToday',$date);
//          $command2->bindParam(':lsgiId',$lsgiId);
//          $command2->execute();
// }
  public function getSubsciptions($lsgiId){
         $qry = "INSERT INTO payment_request(amount,account_id_customer,service_id,created_at,modified_at,requested_date,is_subscription_payment) SELECT lsgi_service_fee.amount AS amount, account_service.account_id AS account_id, account_service.package_id AS service_id,:dateToday as created_at, :dateToday as modified_at, :dateToday as requested_date,:is_subscription_payment as is_subscription_payment FROM account_service LEFT JOIN lsgi_service_fee ON lsgi_service_fee.service_id=account_service.package_id left join payment_request on payment_request.account_id_customer=account_service.account_id
          WHERE (lsgi_service_fee.status=1) AND (account_service.status=1) AND (lsgi_service_fee.lsgi_id=:lsgiId) AND (lsgi_service_fee.payment_collection_type=1) and (payment_request.account_id_customer,DATE(payment_request.requested_date)) NOT IN (select pr.account_id_customer,date(:dateToday) FROM payment_request pr where status = 1 and pr.is_subscription_payment=1 and pr.is_closed=0)
          group by account_service.account_id,account_service.package_id
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

         $qry2 = 'UPDATE lsgi set subscription_fee_collection_date=:dateToday where id=:lsgiId';
        
         $command2 =  Yii::$app->db->createCommand($qry2);
         
         $lsgiId = $lsgiId;
         $dateToday = date('Y-m-d H:i:s');
         $dateNew = date('Y-m-d H:i:s');
         $date =  date('Y-m-d H:i:s', strtotime($dateNew. ' +30 days'));
         $command2->bindParam(':dateToday',$date);
         $command2->bindParam(':lsgiId',$lsgiId);
         $command2->execute();
}

public function schedulePaymentLsgi(){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
      foreach ($modelLsgi as $lsgiData) {
      $lsgiId = $lsgiData->id;
      $date = date('Y-m-d H:i:s');
      if($lsgiData->subscription_fee_collection_date<=$date)
      $this->getSubsciptions($lsgiId);
    }
  }
  public function scheduleCompletionTime(){
    // Yii::$app->db->cache->flush();
     $qry = 'SELECT * FROM service_request 
                    LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                     WHERE service_request.is_cancelled = 0 and service_assignment.expiry_ts<:dateToday and service_assignment.status =1  and service_request.status =1';
                  $dateToday = date('Y-m-d H:i:s');
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':dateToday',$dateToday);
                  $list = $command->queryAll();
      if($list)
      {
        foreach ($list as $value) {
                            $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$value['ward_id']])->andWhere(['status'=>1])->one();
                            if($modelHksWard)
                            {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               { 
                                $value['time_of_completion_points_calculated'] = 1;
                                $value['is_cancelled'] = 1;
                                $qry1 = "SELECT max(performance_point) as performance_point FROM `evaluation_config_completion_time` where status=1";

                                 $command1 =  Yii::$app->db->createCommand($qry1);
                                $data1 = $command1->queryAll();
                                $point1 = $data1[0];
                  
                                // $minutes = (strtotime("2012-09-21 12:12:22") - time()) / 60;
                                // print_r($minutes);die();
                                $start_date = date('Y-m-d H:i:s',strtotime($value['requested_datetime']));
                                $today = date('Y-m-d H:i:s');
                                $todayDate = date('Y-m-d H:i:s',strtotime($today));
                                // $since_start = $start_date->diff($todayDate);
                                // $minutes = $since_start->days * 24 * 60;
                                // $minutes += $since_start->h * 60;
                                // $minutes += $since_start->i;
                                 $minutes = (time()-strtotime($value['requested_datetime'])) / 60;

                                $modelEvaluationConfigCompletionTime = EvaluationConfigCompletionTime::find()
                                ->where(['<=','start_value_minutes',$minutes])
                                ->andWhere(['>=','end_value_minutes',$minutes])
                                ->andWhere(['lsgi_id'=>$value['lsgi_id']])
                                ->andWhere(['status'=>1])->one();
                                if($modelEvaluationConfigCompletionTime)
                                {
                                    $performance_point_value = $modelEvaluationConfigCompletionTime->performance_point;
                                }
                                else
                                {
                                 
                                    $performance_point_value = $point1['performance_point'];
                                }
                                $performance_point_max = $point1['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $value['performance_point'] = $value['performance_point']+$performance_point_value; 
                                
                                if($modelHks->save(false))
                                {
                                  $qry2 = 'UPDATE service_request set performance_point=:performance,time_of_completion_points_calculated=1,is_cancelled=1 where id=:id';
        
         $command2 =  Yii::$app->db->createCommand($qry2);
         $performance = $value['performance_point']+$performance_point_value; 
         $id = $value['id']; 
         $command2->bindParam(':performance',$performance);
         $command2->bindParam(':id',$id);
         $command2->execute();
                                }
                               }
                            }
          
        }
      }
  }

  public function scheduleComplaintsCount($lsgiId=null,$hks=null,$ward=null,$date1=null){
    // print_r($lsgiId);die();
     $qry = 'SELECT * FROM service_request
                    LEFT JOIN service on service.id=service_request.service_id 
                     WHERE service_request.created_at<:dateToday and service_request.created_at>:date1 and  service_request.status =1 and service.status=1 and service.type=2 and service_request.lsgi_id=:lsgiId and ward_id=:ward';
                  $dateToday = date('Y-m-d H:i:s');
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':dateToday',$dateToday);
                  $command->bindParam(':date1',$date1);
                  $command->bindParam(':lsgiId',$lsgiId);
                  $command->bindParam(':ward',$ward);
                  $list = $command->queryAll();
                  $count        = count($list);
                   $qryNew = 'SELECT count(customer.id) as count FROM customer
                    LEFT JOIN green_action_unit_ward on customer.ward_id=green_action_unit_ward.ward_id 
                     WHERE customer.status=1 and green_action_unit_ward.status=1 and green_action_unit_ward.green_action_unit_id=:hks';
                  $commandNew =  Yii::$app->db->createCommand($qryNew);
                  $commandNew->bindParam(':hks',$hks);
                  $listNew = $commandNew->queryOne();
                  $totalCount        = $listNew['count'];
      if($count>1)
      {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               { 
                                $qry1 = "SELECT max(performance_point) as performance_point FROM `evaluation_config_complaints_count` where status=1";

                                 $command1 =  Yii::$app->db->createCommand($qry);
                                $data1 = $command1->queryAll();
                                $point1 = $data1[0];
                               $count = ($count/$totalCount)*100;
                                $modelEvaluationConfigurationComplaintsCount = EvaluationConfigComplaintsCount::find()
                                ->where(['<=','start_value_count',$count])
                                ->andWhere(['>=','end_value_minutes',$count])
                                ->andWhere(['status'=>1])
                                ->andWhere(['lsgi_id'=>$lsgiId])
                                ->one();
                                if($modelEvaluationConfigurationComplaintsCount)
                                {
                                    $performance_point_value = $modelEvaluationConfigurationComplaintsCount->performance_point;
                                }
                                else
                                {
                                    $performance_point_value = $point1['performance_point'];
                                }
                                $performance_point_max = $point1['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelHks->save(false);
                                
                            }
                            $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
                                if($modelLsgi)
                                {
                                  $modelLsgi->last_complaint_resolution_calculated_at = date('Y-m-d H:i:s');
                                  $modelLsgi->save(false);
                                }
          

                               }
                                
      
  }
  public function scheduleCompletionPercentage($lsgiId=null,$hks=null,$ward=null,$date1=null,$service_id=null){
    // $ward = 7;
    // $hks = 16;
     $qry = 'SELECT * FROM service_request 
                    LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                     WHERE service_assignment.servicing_status_option_id >  0 and service_assignment.status =1  and service_request.status =1 and service_request.lsgi_id=:lsgiId and service_request.ward_id=:ward and service_request.created_at<:dateToday and service_request.created_at >:date1 and service_request.service_id=:service_id';
                  $dateToday = date('Y-m-d H:i:s');
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':lsgiId',$lsgiId);
                  $command->bindParam(':ward',$ward);
                  $command->bindParam(':dateToday',$dateToday);
                  $command->bindParam(':date1',$date1);
                  $command->bindParam(':service_id',$service_id);
                  $list = $command->queryAll();

                  $qry1 = 'SELECT * FROM service_request 
                    LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                     WHERE service_assignment.status =1  and service_request.status =1 and service_request.lsgi_id=:lsgiId and service_request.ward_id=:ward and service_request.created_at<:dateToday and service_request.created_at>:date1 and service_request.service_id=:service_id';
                  $dateToday = date('Y-m-d H:i:s');
                  $command1 =  Yii::$app->db->createCommand($qry1);
                  $command1->bindParam(':lsgiId',$lsgiId);
                  $command1->bindParam(':ward',$ward);
                  $command1->bindParam(':dateToday',$dateToday);
                  $command1->bindParam(':date1',$date1);
                  $command1->bindParam(':service_id',$service_id);
                  $list1 = $command1->queryAll();
                  
                  $totalCount        = count($list1);
                  $count        = count($list);
                   // print_r($count);
                   // print_r($totalCount);die();

      if($count>1)
      {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               { 
                                $qry2 = 'SELECT max(performance_point) as performance_point FROM `evaluation_config_completion_percentage` where status=1 and service_id=:service_id';
                                // and service_id=:service_id

                                 $command2 =  Yii::$app->db->createCommand($qry2);
                                 $command2->bindParam(':service_id',$service_id);
                                $data1 = $command2->queryAll();
                                $point1 = $data1[0];
                               $count = ($count/$totalCount)*100;
                               
                                $modelEvaluationConfigurationCompletionPercentage = EvaluationConfigCompletionPercentage::find()
                                ->where(['<=','start_value_percent',$count])
                                ->andWhere(['>=','end_value_percent',$count])
                                ->andWhere(['lsgi_id'=>$lsgiId])
                                ->andWhere(['service_id'=>$service_id])
                                ->andWhere(['status'=>1])->one();
                                $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
                                if($modelEvaluationConfigurationCompletionPercentage)
                                {
                                    $performance_point_value = $modelEvaluationConfigurationCompletionPercentage->performance_point;
                                }
                                elseif($point1['performance_point'])
                                {
                                    $performance_point_value = $point1['performance_point'];
                                }
                                elseif($modelLsgi->default_service_point)
                                {
                                  $performance_point_value = $modelLsgi->default_service_point;
                                }
                                else
                                {
                                  $performance_point_value = 0;
                                }
                                $performance_point_max = $point1['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelHks->save(false);
                                
                                
                               }
                               $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
                            if($modelLsgi)
                                {
                                  $modelLsgi->last_service_completion_calculated_at = date('Y-m-d H:i:s');
                                  $modelLsgi->save(false);
                                }
                            }
                            
          
      
  }

  public function scheduleComplaintResolution($lsgiId=null,$hks=null,$ward=null,$date1=null,$hours=null){
    // $ward = 7;
    // $hks = 16;
     $qry = 'SELECT * FROM service_request 
                    LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                    LEFT JOIN service on service.id=service_request.service_id
                     WHERE service_assignment.servicing_status_option_id >  0 and service_assignment.status =1  and service_request.status =1 and service_request.lsgi_id=:lsgiId and service_request.ward_id=:ward and service_request.created_at<:dateToday and service_request.created_at >:date1 and service.type=2 and service.status=1';
                  $dateToday = date('Y-m-d H:i:s');
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':lsgiId',$lsgiId);
                  $command->bindParam(':ward',$ward);
                  $command->bindParam(':dateToday',$dateToday);
                  $command->bindParam(':date1',$date1);
                  $list = $command->queryAll();

                  $qry1 = 'SELECT * FROM service_request 
                    LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                    LEFT JOIN service on service.id=service_request.service_id
                     WHERE service_assignment.status =1  and service_request.status =1 and service_request.lsgi_id=:lsgiId and service_request.ward_id=:ward and service_request.created_at<:dateToday and service_request.created_at>:date1 and service.type=2 and service.status=1';
                  $dateToday = date('Y-m-d H:i:s');
                  $command1 =  Yii::$app->db->createCommand($qry1);
                  $command1->bindParam(':lsgiId',$lsgiId);
                  $command1->bindParam(':ward',$ward);
                  $command1->bindParam(':dateToday',$dateToday);
                  $command1->bindParam(':date1',$date1);
                  $list1 = $command1->queryAll();
                  
                  $totalCount        = count($list1);
                  $count        = count($list);
                   // print_r($count);
                   // print_r($totalCount);die();

      if($count>1)
      {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               { 
                                $qry2 = 'SELECT max(performance_point) as performance_point FROM `evaluation_config_complaints_resolution` where status=1 and hours=:hours';
                                // and service_id=:service_id

                                 $command2 =  Yii::$app->db->createCommand($qry2);
                                 $command2->bindParam(':hours',$hours);
                                $data1 = $command2->queryAll();
                                $point1 = $data1[0];
                               $count = ($count/$totalCount)*100;
                               
                                $modelEvaluationConfigurationComplaintResoltion = EvaluationConfigComplaintsResolution::find()
                                ->where(['<=','start_value_percent',$count])
                                ->andWhere(['>=','end_value_percent',$count])
                                ->andWhere(['lsgi_id'=>$lsgiId])
                                ->andWhere(['hours'=>$hours])
                                ->andWhere(['status'=>1])->one();
                                $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
                                if($modelEvaluationConfigurationComplaintResoltion)
                                {
                                    $performance_point_value = $modelEvaluationConfigurationComplaintResoltion->performance_point;
                                }
                                elseif($point1['performance_point'])
                                {
                                    $performance_point_value = $point1['performance_point'];
                                }
                                else
                                {
                                  $performance_point_value = 0;
                                }
                                $performance_point_max = $point1['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                if($modelHks->save(false))
                                {

                                  $qry3 = 'UPDATE service_request set performance_point=:performance,time_of_complaint_resolution_calculated=1,is_cancelled=1 
                                  LEFT JOIN service_assignment ON service_request.id = service_assignment.service_request_id  
                    LEFT JOIN service on service.id=service_request.service_id
                                  where id=:id and service_assignment.servicing_status_option_id >  0 and service_assignment.status =1  and service_request.status =1 and service_request.lsgi_id=:lsgiId and service_request.ward_id=:ward and service_request.created_at<:dateToday and service_request.created_at >:date1 and service.type=2 and service.status=1';
        $dateToday = date('Y-m-d H:i:s');
         $command2 =  Yii::$app->db->createCommand($qry3);
         $performance = $value['performance_point']+$performance_point_value; 
         $id = $value['id']; 
         $command2->bindParam(':performance',$performance);
         $command2->bindParam(':id',$id);
          $command2->bindParam(':lsgiId',$lsgiId);
                  $command2->bindParam(':ward',$ward);
                  $command2->bindParam(':dateToday',$dateToday);
                  $command2->bindParam(':date1',$date1);
         $command2->execute();
                                }
                                
                                
                               }
                               $modelLsgi = Lsgi::find()->where(['id'=>$lsgiId])->andWhere(['status'=>1])->one();
                            if($modelLsgi)
                                {
                                  $modelLsgi->last_complaint_resolution_calculated_at = date('Y-m-d H:i:s');
                                  $modelLsgi->save(false);
                                }
                            }
                            
          
      
  }
  public function scheduleComplaintsCountLsgi(){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
       foreach ($modelLsgi as $lsgiData) {
         $modelHks = GreenActionUnit::find()->where(['lsgi_id'=>$lsgiData->id])->andWhere(['status'=>1])->all();
        foreach ($modelHks as $hksData) {
          $modelHksWards = GreenActionUnitWard::find()->where(['green_action_unit_id'=>$hksData->id])->all();
          foreach ($modelHksWards as $modelHksWard) {
      $date1 = $lsgiData->last_complaint_count_points_calculated_at;
      $date2 = date('Y-m-d H:i:s');
      $hours = round((strtotime($date2) - strtotime($date1))/3600, 1);
        if($hours>=$lsgiData->complaints_count_rating_calculation_interval_hours){
          $this->scheduleComplaintsCount($lsgiData->id,$hksData->id,$modelHksWard->ward_id,$date1);
        }

        }

exit;
 }
   }
}
public function scheduleCompletionPercentageLsgi(){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
       foreach ($modelLsgi as $lsgiData) {
         $modelHks = GreenActionUnit::find()->where(['lsgi_id'=>$lsgiData->id])->andWhere(['status'=>1])->all();
        foreach ($modelHks as $hksData) {
          $modelHksWards = GreenActionUnitWard::find()->where(['green_action_unit_id'=>$hksData->id])->all();
          foreach ($modelHksWards as $modelHksWard) {
      $date1 = $lsgiData->last_service_completion_calculated_at;
      $date2 = date('Y-m-d H:i:s');
      $modelServices = Service::find()->where(['status'=>1])->andWhere(['type'=>1])->all();
      foreach ($modelServices as $modelService) {
        if(strtotime($date1)<strtotime($date2)){
          $this->scheduleCompletionPercentage($lsgiData->id,$hksData->id,$modelHksWard->ward_id,$date1,$modelService->id);
        }
      }
        }

exit;
 }
   }
}

public function scheduleComplaintResolutionLsgi(){
      $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
       foreach ($modelLsgi as $lsgiData) {
         $modelHks = GreenActionUnit::find()->where(['lsgi_id'=>$lsgiData->id])->andWhere(['status'=>1])->all();
        foreach ($modelHks as $hksData) {
          $modelHksWards = GreenActionUnitWard::find()->where(['green_action_unit_id'=>$hksData->id])->all();
          foreach ($modelHksWards as $modelHksWard) {
      $date1 = $lsgiData->last_complaint_resolution_calculated_at;
      $date2 = date('Y-m-d H:i:s');
      $hours = round((strtotime($date2) - strtotime($date1))/3600, 1);
      // $modelServices = Service::find()->where(['status'=>1])->andWhere(['type'=>1])->all();
      // foreach ($modelServices as $modelService) {
        if($hours<=24){
          $this->scheduleComplaintResolution($lsgiData->id,$hksData->id,$modelHksWard->ward_id,$date1,$hours);
        }
      // }
        }

exit;
 }
   }
}
}
