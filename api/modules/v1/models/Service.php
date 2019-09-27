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
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status','type','is_public'], 'integer'],
            [['created_at', 'modified_at','waste_collection_method','sort_order','ask_waste_quality','ask_waste_quantity'], 'safe'],
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
       $connection->createCommand()->update('service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getServices()
    {
        $service =  Service::find()->where(['status'=> 1])->all();
        return $service;
    }
    public function getGt()
    {
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->andWhere(['account.role' => 'gt'])
                ->all();
        return $gt;

    }
     public function getFkImage()
     {
       return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status'=>1]);
     }
    public static function findByName($name,$serviceType) {
    $qry = static::getAllQuery()->andWhere(['name'=>$name,'type'=>$serviceType]);
    return $qry;
  }
  public function getAllQuery() {
        return static::find()->where(['service.status'=>1]);
        // ->andWhere(['service.is_public'=>1])
    }
}
