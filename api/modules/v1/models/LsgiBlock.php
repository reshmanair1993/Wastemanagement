<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "isgi".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $assembly_constituency_id
 * @property int $isgi_type_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class LsgiBlock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lsgi_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['assembly_constituency_id', 'lsgi_type_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'code'], 'string', 'max' => 200],
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
            'code' => Yii::t('app', 'Code'),
            'assembly_constituency_id' => Yii::t('app', 'Assembly Constituency'),
            'assemblyConstituency.name' => Yii::t('app', 'Assembly Constituency'),
            'lsgi_type_id' => Yii::t('app', 'Lsgi Type'),
            'lsgiType.name' => Yii::t('app', 'Lsgi Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
      return static::find(['status'=>1])->orderby('lsgi_block.sort_order ASC');
    }
     public function deleteLsgi($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('lsgi_block', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getFkAssemblyConstituency()
      {
        return $this->hasOne(AssemblyConstituency::className(), ['id' => 'assembly_constituency_id']);
      }
      public function getFkLsgiType()
      {
        return $this->hasOne(LsgiType::className(), ['id' => 'lsgi_type_id']);
      }
      public function getConstituency()
    {
        $constituency =  AssemblyConstituency::find()->where(['status'=> 1])->all();
        return $constituency;
    }
    public function getType()
    {
        $type =  LsgiType::find()->where(['status'=> 1])->all();
        return $type;
    }
}
