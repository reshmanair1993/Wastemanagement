<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "assembly_constituency_type".
 *
 * @property int $id
 * @property string $name
 * @property int $parliament_constituency_id_1
 * @property int $parliament_constituency_id_2
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AssemblyConstituency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assembly_constituency';
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
    public function rules()
    {
        return [
            [['name','district_id'], 'required'],
            [['parliament_constituency_id_1', 'parliament_constituency_id_2', 'status'], 'integer'],
            [['created_at', 'modified_at','sort_order'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'parliament_constituency_id_1' => Yii::t('app', 'Parliament Constituency Id 1'),
            'parliament_constituency_id_2' => Yii::t('app', 'Parliament Constituency Id 2'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
	public function deleteAssemblyConstituency($id) {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('assembly_constituency', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
  }
    public function getDistricts()
    {
        $districts =  District::find()->where(['status'=> 1])->all();
        return $districts;
    }
    public function getConstituency1()
    {
        $constituency =  ParliamentConstituency::find()->where(['status'=> 1])->andWhere(['constituency_type'=>1])->all();
        return $constituency;
    }
    public function getConstituency2()
    {
        $constituency =  ParliamentConstituency::find()->where(['status'=> 1])->andWhere(['constituency_type'=>2])->all();
        return $constituency;
    }
    public function getFkDistrict()
     {
       return $this->hasOne(District::className(), ['id' => 'district_id']);
     }
     public function getFkConstituency1()
      {
        return $this->hasOne(ParliamentConstituency::className(), ['id' => 'parliament_constituency_id_1']);
      }
      public function getFkConstituency2()
      {
        return $this->hasOne(ParliamentConstituency::className(), ['id' => 'parliament_constituency_id_2']);
      }
       public function deleteAssemblyconstituency_type($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('assembly_constituency_type', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
