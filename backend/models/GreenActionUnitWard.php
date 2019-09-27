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
class GreenActionUnitWard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'green_action_unit_ward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['green_action_unit_id', 'ward_id'], 'required'],
            [['green_action_unit_id', 'ward_id', 'status'], 'integer'],
            [['created_at', 'modified_at','service_id'], 'safe'],
              [['service_id'],'new_and_unique'],
        ];
    }
    public function new_and_unique($attribute,$params)
    {
      $serviceIds = json_decode($this->service_id);
      $flag =0; 
      $service = null;
        $list  = GreenActionUnitWard::find()->where(['ward_id'=>$this->ward_id])->andWhere(['status'=>1])->all();
        foreach ($list as $key => $value) {
          if($value->service_id)
          {
            $serviceIdList = json_decode($value->service_id);
            foreach ($serviceIdList as $key => $value) {
              if(in_array($value, $serviceIds)){
              $flag = 1;
              $service = $value;
            }
            }
          }
        }
        if($flag==1&&$service){
          $service_name = Service::find()->where(['id'=>$service])->one();
          $name = isset($service_name->name)?$service_name->name:'service';
           $this->addError($attribute, $name.' already taken ');
        }
       
       
    }
    public function getServices($ids)
  {
    $name = null;
    $serviceIds = json_decode($ids);
   foreach ($serviceIds as $serviceId) {
    $modelService = Service::find()->where(['id'=>$serviceId])->andWhere(['status'=>1])->one();
     $name.= $modelService->name;
   }
   return $name;
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
            'ward_id' => Yii::t('app', 'Ward'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteUnit($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('green_action_unit_ward', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getFkWard()
      {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
      }
      public function getFkGreenActionUnit()
      {
        return $this->hasOne(GreenActionUnit::className(), ['id' => 'green_action_unit_id']);
      }
       public function getWardName()
  {
    $wardName = '';

    $ward = $this->fkWard;
    if($ward != null){
        $wardName = $ward->name_en;
    }
    return $wardName;
  }
    //   public function getWard($lsgiId=null,$category=null)
    // {
    //   // SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward WHERE status = 1 ) AND (`lsgi_id`=13)

    //     // $wards =  Ward::find()
    //     // ->where(['ward.status'=> 1])
    //     // ->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
    //     // ->andWhere(['OR',['green_action_unit_ward.ward_id'=>null] ,['green_action_unit_ward.status'=>0]])
    //     // ->andWhere(['lsgi_id'=>$lsgiId])->all();

    //     $wards = "SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward left join green_action_unit on green_action_unit.id= green_action_unit_ward.green_action_unit_id WHERE green_action_unit_ward.status = 1 and green_action_unit.residence_category_id=:category  ) AND (`lsgi_id`=:lsgi) and ward.status=1";
    //      $command =  Yii::$app->db->createCommand($wards);
    //      $command->bindParam(':lsgi',$lsgiId);
    //      $command->bindParam(':category',$category);
    //      $wards = $command->queryAll();
    //     return $wards;
    // }
  public function getWard($lsgiId=null,$category=null,$id=null)
    {
      // SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward WHERE status = 1 ) AND (`lsgi_id`=13)

        // $wards =  Ward::find()
        // ->where(['ward.status'=> 1])
        // ->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        // ->andWhere(['OR',['green_action_unit_ward.ward_id'=>null] ,['green_action_unit_ward.status'=>0]])
        // ->andWhere(['lsgi_id'=>$lsgiId])->all();
      if($category!=3){
        $wards = "SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward left join green_action_unit on green_action_unit.id= green_action_unit_ward.green_action_unit_id WHERE green_action_unit_ward.status = 1 and green_action_unit.residence_category_id=:category  ) AND (`lsgi_id`=:lsgi) and ward.status=1";
        $command =  Yii::$app->db->createCommand($wards);
         $command->bindParam(':lsgi',$lsgiId);
         $command->bindParam(':category',$category);
      }
      else
      {
        $wards = "SELECT `ward`.* FROM `ward`  WHERE id NOT IN (SELECT ward_id FROM green_action_unit_ward left join green_action_unit on green_action_unit.id= green_action_unit_ward.green_action_unit_id WHERE green_action_unit_ward.status = 1 and green_action_unit.id=:id  ) AND (`lsgi_id`=:lsgi) and ward.status=1";
        $command =  Yii::$app->db->createCommand($wards);
         $command->bindParam(':lsgi',$lsgiId);
         $command->bindParam(':id',$id);
      }
         $wards = $command->queryAll();
        return $wards;
    }
    public function getUnit()
    {
        $type =  GreenActionUnit::find()->where(['status'=> 1])->all();
        return $type;
    }
}
