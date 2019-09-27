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
class CameraServiceRequestAssignment extends \yii\db\ActiveRecord
{
  public $location;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_service_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['camera_id','account_id_technician','service_id','camera_servicing_status_option_id','camera_service_request_id', 'status'], 'integer'],
            [['camera_id','account_id_technician','service_id','camera_servicing_status_option_id','camera_service_request_id', 'status','date', 'created_at', 'modified_at','lat_update_from','lng_updated_from'], 'safe'],
            [['camera_servicing_status_option_id','lat_update_from','lng_updated_from'], 'required','on'=>'add-status'],
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
            'name' => Yii::t('app', 'Name'),
            'camera_id' => Yii::t('app', 'Camera Id'),
            'account_id_technician' => Yii::t('app', 'Account Id Technician'),
            'service_id' => Yii::t('app', 'Service Id'),
            'camera_servicing_status_option_id' => Yii::t('app', 'Camera Servicing Status Option Id'),
            'camera_service_request_id' => Yii::t('app', 'Camera Service Request Id'),
            'date' => Yii::t('app', 'Date'),
            'location' => Yii::t('app', 'Location'),
            'lat_update_from' => Yii::t('app', 'lat update from'),
            'lng_updated_from' => Yii::t('app', 'lng_update_from'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function getFkCamera()
    {
      return $this->hasOne(Camera::className(), ['id' => 'camera_id']);
    }
    public function getFkCameraService()
    {
      return $this->hasOne(CameraService::className(), ['id' => 'service_id']);
    }
    public function getFkStatus()
    {
      return $this->hasOne(CameraServicingStatusOption::className(), ['id' => 'camera_servicing_status_option_id']);
    }
    public function getFkTechnician()
    {
        $modelAccount = Account::find()->where(['id' => $this->account_id_technician])->one();
        if($modelAccount){
          return $modelPerson = Person::find()->where(['id'=>$modelAccount->person_id])->one();
        }
    }
    public function getFkServiceStatus()
    {
        return $this->hasOne(CameraServicingStatusOption::className(), ['id' => 'camera_servicing_status_option_id'])->andWhere(['status'=>1]);
    }
    public function getTechnician($id)
    {
        $modelAccount = Account::find()->where(['id' => $id])->one();
        if($modelAccount){
          return $modelPerson = Person::find()->where(['id'=>$modelAccount->person_id])->one();
        }
    }
    public function deleteCameraService($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('camera_service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
 public function getFkAccount()
 {
   return $this->hasOne(Account::className(), ['id' => 'account_id_technician']);
 }
}
