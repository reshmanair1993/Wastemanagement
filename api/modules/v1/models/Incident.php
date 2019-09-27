<?php

namespace  api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "incident".
 *
 * @property int $id
 * @property int $incident_type_id
 * @property int $camera_id
 * @property int $image_id
 * @property int $file_video_id
 * @property int $duration
 * @property int $is_approved
 * @property string $captured_at
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Incident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'incident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['incident_type_id', 'camera_id', 'image_id', 'file_video_id', 'duration', 'is_approved', 'status','offence_image_id1','offence_image_id2','offence_image_id3'], 'integer'],
            [['captured_at', 'created_at', 'modified_at','vehicle_number','vehicle_type','incident_timestamp'], 'safe'],
            [['incident_type_id', 'camera_id'],'required'],
        ];
    }
    public function behaviors()
    {
       return [
           [

           'class' => TimestampBehavior::className(),
           'createdAtAttribute' => 'created_at',
           'updatedAtAttribute' => 'modified_at',
           'value' => new Expression('NOW()'),
               // if you're using datetime instead of UNIX timestamp:
               // 'value' => new Expression('NOW()'),
           ],
       ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'incident_type_id' => Yii::t('app', 'Incident Type'),
            'camera_id' => Yii::t('app', 'Camera'),
            'image_id' => Yii::t('app', 'Image'),
            'file_video_id' => Yii::t('app', 'Video'),
            'duration' => Yii::t('app', 'Duration'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'captured_at' => Yii::t('app', 'Captured At'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getSharingUrl() {
        $url = "http://139.162.54.79/wastemanagement/backend/web/incidents/incident-preview?id=".$this->id;
        
        return $url;
    } 
    public static function getAllQuery()
    {
      return static::find(['status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkCamera()
     {
       return $this->hasOne(Camera::className(), ['id' => 'camera_id'])->andWhere(['status'=>1]);
     }
     public function getFkImage()
     {
       return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status'=>1]);
     }
     public function getFkFileVideo()
     {
       return $this->hasOne(FileVideo::className(), ['id' => 'file_video_id'])->andWhere(['status'=>1]);
     }
      public function getFkIncidentType()
     {
       return $this->hasOne(IncidentType::className(), ['id' => 'incident_type_id'])->andWhere(['status'=>1]);
     }
      public function getFkMemo()
     {
       return $this->hasOne(Memo::className(), ['incident_id' => 'id'])->andWhere(['status'=>1]);
     }
     public function getMemo()
    {
        $memo = null;
        if ($this->fkMemo)
        {
                $memo = $this->fkMemo->id;
        }

        return $memo;
    }
     public function getImageHeight($id)
    {
      $height = null;
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'image_height'])->one();
      if($modelIncidents){
         $height = $modelIncidents->value;
      }
      return $height;
    }
    public function getImageWidth($id)
    {
      $width = null;
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'image_width'])->one();
      if($modelIncidents){
          $width = $modelIncidents->value;
      }
      return $width;
    }
    public function getVideoHeight($id)
    {
      $height = null;
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'video_height'])->one();
      if($modelIncidents){
          $height =  $modelIncidents->value;
      }
      return $height;
    }
    public function getVideoWidth($id)
    {
      $width = null;
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'video_width'])->one();
      if($modelIncidents){
          $width = $modelIncidents->value;
      }
      return $width;
    }

}
