<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "assembly_contituency".
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['name','district_id'], 'required'],
          [['parliament_constituency_id_1', 'parliament_constituency_id_2', 'status'], 'integer'],
          [['created_at', 'modified_at'], 'safe'],
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
    public static function getAllQuery() {
      return static::find()->where(['assembly_constituency.status'=>1])->orderby('assembly_constituency.sort_order ASC');
    }
    public function getDistricts()
    {
        $districts =  District::find()->where(['status'=> 1])->all();
        return $districts;
    }
    public function getFkParliamentConstituency1()
    {
        $constituency =  ParliamentConstituency::find()->where(['status'=> 1])->andWhere(['constituency_type'=>1])->all();
        return $constituency;
    }
    public function getFkParliamentConstituency2()
    {
        $constituency =  ParliamentConstituency::find()->where(['status'=> 1])->andWhere(['constituency_type'=>2])->all();
        return $constituency;
    }
    public function getFkConstituency1()
    {
       return $this->hasOne(ParliamentConstituency::className(), ['id' => 'parliament_constituency_id_1']);
    }
    public function getFkDistrict()
    {
       return $this->hasOne(District::className(), ['id' => 'district_id']);
	}
      public function getFkConstituency2()
      {
        return $this->hasOne(ParliamentConstituency::className(), ['id' => 'parliament_constituency_id_2']);
      }
       public function deleteAssemblyContituency($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('assembly_constituency', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
