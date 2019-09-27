<?php

namespace backend\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "monitoring_group".
 *
 * @property int $id
 * @property int $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MonitoringGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_group';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Group Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    // public function getMonitoringGroups()
    // {
    //   $ret = [];
    //   $modelMonitoringGroupCameras = $this->fkMonitoringGroups;
    //   foreach($modelMonitoringGroupCameras as $modelMonitoringGroupCamera)
    //   {
    //     $modelGroup = MonitoringGroup::find()->where(['id' => $modelMonitoringGroupCamera->monitoring_group_id,'status' => 1])->one();
    //     $modelMonitoringGroup = $modelGroup->name;
    //     if($modelMonitoringGroup){
    //       $ret[] = $modelMonitoringGroup;
    //     }
    //   }
    //   return $ret;
    // }
    public function getGroup($id)
   {
       $group =  MonitoringGroup::find()->where(['account_id_created_by' => $id,'status'=> 1])->all();
       return $group;
   }
    public function getFkMonitoringGroups()
    {
        return $this->hasMany(MonitoringGroupCamera::className(), ['monitoring_group_id' => 'id'])->andWhere(['status' => 1]);
    }
    public function getCamera()
    {
      $ret = [];
      $modelCameras = $this->fkMonitoringGroups;
      // $modelCameras = MonitoringGroupCamera::find()->where(['monitoring_group_id' => $id ,'status' => 1])->all();
      foreach ($modelCameras as $modelCamera) {
        $model = Camera::find()->where(['id' => $modelCamera->camera_id,'status' => 1])->one();
        if($model){
        $modelMonitoringGroupCamera = $model->name;
        if($modelMonitoringGroupCamera){
          $ret[] = $modelMonitoringGroupCamera;
      }
    }
  }
    return $ret;
  }
  public function getFkMonitoringGroupUsers()
  {
      return $this->hasMany(MonitoringGroupUser::className(), ['monitoring_group_id' => 'id'])->andWhere(['status' => 1]);
  }
    public function getUsers()
    {
      $ret = [];
      $modelUsers = $this->fkMonitoringGroupUsers;
      // $modelUsers = MonitoringGroupUser::find()->where(['monitoring_group_id' => $id ,'status' => 1])->all();
      foreach ($modelUsers as $modelUser) {
        $model = Account::find()->where(['id' => $modelUser->account_id,'status' => 1])->one();
        if($model){
        $modelMonitoringGroupUser = $model->username;
        if($modelMonitoringGroupUser){
            $ret[] = $modelMonitoringGroupUser;
        }
      }
    }
      return $ret;
    }
    public function deleteGroup($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('monitoring_group', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
 public static function getAllUserQuery($id)
 {
    $query = MonitoringGroupUser::find()
    ->leftJoin('account','account.id = monitoring_group_user.account_id')
    ->where(['monitoring_group_user.monitoring_group_id' => $id,'monitoring_group_user.status'=>1,'account.status'=>1])
    ->orderBy(['id' => SORT_DESC]);
    return $query;
 }
 public static function getAllCameraQuery($id)
 {
    $query = MonitoringGroupCamera::find()
    ->leftJoin('camera','camera.id = monitoring_group_camera.camera_id')
    ->where(['monitoring_group_camera.monitoring_group_id' => $id,'monitoring_group_camera.status'=>1,'camera.status'=>1])
    ->orderBy(['id' => SORT_DESC]);
    return $query;
 }
 public function unFormatDates($date) {
   // $utilitiesRef = Yii::$app->utilities;
   // $dbDateFormat = $utilitiesRef->getDateFormatDB();
   // $fields = ['created_at','modified_at'];
   // foreach($fields as $field) {
   //   $val = $this->{$field};
   //   if(!($val && ($dateTime = \DateTime::createFromFormat($dbDateFormat,$val))))
   //     continue;
   //   $dateFormatJs = $utilitiesRef->getDateFormat();
   //   $this->{$field} = $dateTime->format($dateFormatJs);
   // }
 }


}
