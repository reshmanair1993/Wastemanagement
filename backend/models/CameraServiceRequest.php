<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_request".
 *
 * @property int $id
 * @property int $service_id
 * @property int $acoount_id_customer
 * @property int $account_id_gt
 * @property int $account_id_completed_by
 * @property string $requested_date
 * @property string $servicing_date
 * @property string $remark
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraServiceRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_service_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['camera_id', 'status','service_id'], 'integer'],
            [['camera_id', 'status','request_date','service_id', 'created_at', 'modified_at'], 'safe'],
            [['camera_id','service_id'], 'required'],

        ];
    }

public function behaviors() {
    return [
      [
        'class' => TimestampBehavior::className(),
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => 'modified_at',
        'value' => new Expression('NOW()')
      ]
    ];

 }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'camera_id' => Yii::t('app', 'Camera'),
            'service_id' => Yii::t('app', 'Service'),
            'request_date' => Yii::t('app', 'Request Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteCameraServiceRequest($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('camera_service_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
 public static function getAllQuery()
 {
   $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
   return $query;
 }
 public function getFkCamera()
 {
   return $this->hasOne(Camera::className(), ['id' => 'camera_id'])->andWhere(['status'=>1]);
 }
 public function getFkCameraService()
 {
   return $this->hasOne(CameraService::className(), ['id' => 'service_id']);
 }
 public function getFkTechnician()
 {
     $modelAccount = Account::find()->where(['id' => $this->account_id_technician])->one();
     if($modelAccount){
       return $modelPerson = Person::find()->where(['id'=>$modelAccount->person_id])->one();
     }
 }
   public function getStatus()
  {
      $status  = 'Not assigned';
      $modelServiceAssignment = $this->fkCameraServiceAssignment;
      if($modelServiceAssignment)
      {
      if($modelServiceAssignment->camera_servicing_status_option_id==null){
          $status = 'Pending' ;
      }
      else
      {
        $status = $modelServiceAssignment->fkServiceStatus->value;
      }
      }
    return $status;
  }
  // public function getGt($customerId)
  // {
  //     $modelUser  = Yii::$app->user->identity;
  //     if($modelUser->role=='supervisor'){
  //         $supervisor = $modelUser->id;
  //         $gt =  Person::find()
  //             ->select('account.id as id,person.first_name as first_name')
  //             ->where(['account.status' => 1])
  //             ->leftjoin('account', 'account.person_id=person.id')
  //             // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
  //             // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
  //             ->andWhere(['account.supervisor_id'=>$supervisor])
  //             ->andWhere(['account.role' => 'green-technician'])
  //             ->all();
  //         }
  //         else
  //         {
  //     $accountData = Account::find()->where(['id'=>$customerId])->one();
  //     $customerData = Customer::find()->where(['id'=>$accountData->customer_id])->one();
  //     $gt =  Person::find()
  //             ->select('account.id as id,person.first_name as first_name')
  //             ->where(['account.status' => 1])
  //             ->leftjoin('account', 'account.person_id=person.id')
  //             // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
  //             // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
  //             // ->leftjoin('account_ward','account_ward.account_id=account.id')
  //             // ->andWhere(['account_ward.ward_id'=>$customerData->ward_id])
  //             ->leftjoin('account_authority','account_authority.account_id_gt=account.id')
  //             ->andWhere(['account_authority.account_id_customer' => $accountData->id])
  //             ->andWhere(['account.role' => 'green-technician'])
  //             ->all();
  //
  //         }
  //
  //     return $gt;
  //
  // }
 // public function getTechnician($cameraId)
 // {
 //     $camera = Camera::find()->where(['id' => $cameraId])->one();
 //     if($camera){
 //       $accountData = Account::find()->where(['id'=>$camera->account_id_technician])->one();
 //       $technician = Person::find()
 //       ->select('account.id as id,person.first_name as first_name')
 //       ->where(['account.status' => 1])
 //       ->leftjoin('account', 'account.person_id=person.id')
 //       // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
 //       // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
 //       ->leftjoin('account_ward','account_ward.account_id=account.id')
 //       ->where(['person.id' => $accountData->person_id])
 //       ->andWhere(['account.role' => 'camera-technician'])->all();
 //       return $technician;
 //     }
 // }
 public function getTechnician($cameraId)
 {
     // $modelUser = Yii::$app->user->identity;
     //  $userRole  = $modelUser->role;
     //  if($userRole == 'admin-lsgi'){
     //
     //  }
     $lsgi = Lsgi::find()
       ->leftJoin('ward','ward.lsgi_id= lsgi.id')
       ->leftJoin('camera','camera.ward_id=ward.id')
       ->where(['camera.id'=>$cameraId])
       ->andWhere(['ward.status' =>1,'lsgi.status'=>1,'camera.status'=>1])->one();

     if($lsgi){
       $technician = Person::find()
       ->select('account.id as id,person.first_name as first_name')
       ->leftjoin('account', 'account.person_id=person.id')
       ->leftjoin('account_ward','account_ward.account_id=account.id')
       ->where(['account.lsgi_id'=>$lsgi->id])
       ->andWhere(['account.role' => 'camera-technician','account.status' => 1,'account_ward.status' => 1, 'person.status' => 1])->all();
       return $technician;
     }
 }
 public function getStatusOption($serviceId)
 {
     $status =  CameraServicingStatusOption::find()
             ->where(['camera_servicing_status_option.status' => 1])
             ->andWhere(['camera_servicing_status_option.service_id' => $serviceId])
             ->all();
             // print_r($gt);die();
             // print_r($status);exit;
     return $status;

 }
 public function getFkCameraServiceAssignment()
    {
      return $this->hasOne(CameraServiceRequestAssignment::className(), ['camera_service_request_id' => 'id'])->andWhere(['camera_service_assignment.status'=>1]);
    }
}
