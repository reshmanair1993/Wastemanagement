<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "waste_type".
 *
 * @property int $id
 * @property string $name
 * @property int $waste_category_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class WasteCollectionMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waste_collection_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'waste_category_id'], 'required'],
            [['waste_category_id', 'status','is_public','collection_available','facility_provided_by_system'], 'integer'],
            [['created_at', 'modified_at','building_type','sort_order'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'waste_category_id' => Yii::t('app', 'Waste Category'),
            'is_public' => Yii::t('app', 'Public'),
            'collection_available' => Yii::t('app', 'Collection Available'),
            'fkCategory.name' => Yii::t('app', 'Waste Category'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('waste_collection_method', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getfkCategory()
      {
        return $this->hasOne(WasteCategory::className(), ['id' => 'waste_category_id']);
      }
      public function getCategory()
    {
        $type =  WasteCategory::find()->where(['status'=> 1])->all();
        return $type;
    }
    public function getMethod()
    {
         $method =  WasteCollectionMethod::find()->where(['status'=> 1])->all();
        // $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>2])->all();
        return $method;
    }
    public function getMethodBio()
    {
        // $method =  WasteCollectionMethod::find()->where(['status'=> 1])->all();
        $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>2])->all();
        return $method;
    }
    public function getNonBioMethod()
    {
        $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>3])->all();
        return $method;
    }
    public function getBioMethod()
    {
        $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>2])->all();
        $method=ArrayHelper::map($method, 'id', 'name');
        return $method;
    }
    public function getNonBioMethods()
    {
        $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>3])->all();
        $method=ArrayHelper::map($method, 'id', 'name');
        return $method;
    }
    public function getBioMedicalMethod()
    {
        $method =  WasteCollectionMethod::find()->where(['status'=> 1])->andWhere(['waste_category_id'=>4])->all();
        $method=ArrayHelper::map($method, 'id', 'name');

        return $method;
    }
    public function getAllQuery() {
        return static::find(['status'=>1]);
    }
}
