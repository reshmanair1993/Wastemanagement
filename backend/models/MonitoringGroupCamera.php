<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "monitoring_group_camera".
 *
 * @property int $id
 * @property int $camera_id
 * @property int $monitoring_group_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MonitoringGroupCamera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_group_camera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camera_id', 'monitoring_group_id', 'status'], 'integer'],
            // [['camera_id'],'unique'],
            [['camera_id','monitoring_group_id'],'checkUniqueness'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }
    public function checkUniqueness($attribute,$params)
    {
            $model = MonitoringGroupCamera::find()->where(['camera_id' => $this->camera_id,'monitoring_group_id'=> $this->monitoring_group_id,'status' => 1])->all();
            if($model != null)
                $this->addError('camera_id','This camera already exist');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'camera_id' => Yii::t('app', 'Camera ID'),
            'monitoring_group_id' => Yii::t('app', 'Monitoring Group ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getMonitoringGroupCamera($id)
    {
      $modelCameras = Camera::find()->where(['id' => $id ,'status' => 1])->all();
      foreach ($modelCameras as $modelCamera) {
          return $modelCamera->name;
      }
    }
    public function deleteMonitoringGroupCamera($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('monitoring_group_camera', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
}
