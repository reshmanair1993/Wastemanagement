<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
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
    public $district_id;
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
            [['assembly_constituency_id'  , 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name', 'code'], 'string', 'max' => 200],
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
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'assembly_constituency_id' => Yii::t('app', 'Assembly Constituency'),
            'assemblyConstituency.name' => Yii::t('app', 'Assembly Constituency'), 
            'lsgiType.name' => Yii::t('app', 'Lsgi Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteLsgi($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('lsgi_block', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getAssemblyConstituency()
      {
        return $this->hasOne(AssemblyConstituency::className(), ['id' => 'assembly_constituency_id']);
      } 
      public function getConstituency()
    {
        $constituency =  AssemblyConstituency::find()->where(['status'=> 1])->all();
        return $constituency;
    }
    public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
    public function getConstituencies($id)
    {
        $name = null;

            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $id])->one(); 
            if($assembly_constituency){
                 $name = $assembly_constituency->name;
            }
          
        return $name;
    }
     public function getDistrict($id)
    {
        $name = null;
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;  
            } 
            
        return $name;
    }
}
