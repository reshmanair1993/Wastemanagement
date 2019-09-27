<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "green_action_unit_ward".
 *
 * @property int $id
 * @property int $green_action_unit_id
 * @property int $ward_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class GreenActionUnitService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'green_action_unit_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['green_action_unit_id', 'service_id'], 'required'],
            [['green_action_unit_id', 'service_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'green_action_unit_id' => Yii::t('app', 'Green Action Unit'),
            'service_id' => Yii::t('app', 'Service'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteUnit($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('green_action_unit_service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getFkService()
      {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
      }
      public function getFkGreenActionUnit()
      {
        return $this->hasOne(GreenActionUnit::className(), ['id' => 'green_action_unit_id']);
      }
       public function getWardName()
  {
    $serviceName = '';

    $service = $this->fkService;
    if($service != null){
        $serviceName = $service->name;
    }
    return $serviceName;
  }
      public function getWard($lsgiId=null,$category=null)
    {
      // SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward WHERE status = 1 ) AND (`lsgi_id`=13)

        // $wards =  Ward::find()
        // ->where(['ward.status'=> 1])
        // ->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        // ->andWhere(['OR',['green_action_unit_ward.ward_id'=>null] ,['green_action_unit_ward.status'=>0]])
        // ->andWhere(['lsgi_id'=>$lsgiId])->all();

        $wards = "SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward left join green_action_unit on green_action_unit.id= green_action_unit_ward.green_action_unit_id WHERE green_action_unit_ward.status = 1 and green_action_unit.residence_category_id=:category  ) AND (`lsgi_id`=:lsgi) and ward.status=1";
         $command =  Yii::$app->db->createCommand($wards);
         $command->bindParam(':lsgi',$lsgiId);
         $command->bindParam(':category',$category);
         $wards = $command->queryAll();
        return $wards;
    }
    public function getUnit()
    {
        $type =  GreenActionUnit::find()->where(['status'=> 1])->all();
        return $type;
    }
    public static function getAllQuery()
    {
        return static::find()->where(['status'=> 1]);
    }
}
