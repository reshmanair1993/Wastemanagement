<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 500],
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
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteService($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('camera_service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getServices()
    {
        $service =  CameraService::find()->where(['status'=> 1])->all();
        return $service;
    }
     public function getFkImage()
     {
       return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status'=>1]);
     }
    public static function findByName($name) {
    $qry = static::getAllQuery()->andWhere(['name'=>$name]);
    return $qry;
  }
  public function getAllQuery() {
        return static::find()->where(['camera_service.status'=>1]);
        // ->andWhere(['service.is_public'=>1])
    }
}
