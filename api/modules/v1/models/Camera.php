<?php

namespace  api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "camera".
 *
 * @property int $id
 * @property string $name
 * @property string $serial_no
 * @property double $lat
 * @property double $lng
 * @property int $account_technician_id
 * @property int $ward_id
 * @property int $qr_code_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Camera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_no', 'account_technician_id', 'ward_id', 'qr_code_id', 'status'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['serial_no','host_name'],'unique'],
            [['created_at', 'modified_at','location_name','image_id','host_name'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'serial_no' => Yii::t('app', 'Serial No'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'account_technician_id' => Yii::t('app', 'Account Technician ID'),
            'ward_id' => Yii::t('app', 'Ward ID'),
            'qr_code_id' => Yii::t('app', 'Qr Code ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      return static::find(['status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkWard()
     {
       return $this->hasOne(Ward::className(), ['id' => 'ward_id'])->andWhere(['status'=>1]);
     }
     public function getFkHeartBeat()
     {
       return $this->hasOne(CameraHeartbeat::className(), ['camera_id' => 'id'])->andWhere(['status'=>1]);
     }
      public function getHeartBeat($id)
     {
        $result = null;
       $heartbeat = CameraHeartbeat::find()->where(['status'=>1])->andWhere(['camera_id'=>$id])->orderBy('id DESC')->one();
       if($heartbeat)
       {
        $result = $heartbeat->timestamp;
       }
       return $result;
     }
     public function getIncidentCount($id=null)
      {
        $count = null;
       $modelIncident =  Incident::find()->where(['camera_id'=>$id])->all();
        if($modelIncident){
          $count =0; 
         foreach ($modelIncident as $value) {
                $count = $count+1;
         }
        }
        return $count;
      }
}
