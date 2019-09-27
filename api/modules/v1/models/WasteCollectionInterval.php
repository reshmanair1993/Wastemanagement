<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "waste_collection_interval".
 *
 * @property int $id
 * @property string $name
 * @property int $waste_category_id
 * @property int $building_type_available
 * @property int $is_public
 * @property int $sort_order
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class WasteCollectionInterval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'waste_collection_interval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['waste_category_id', 'is_public', 'sort_order', 'status'], 'integer'],
            [['created_at', 'modified_at','building_type_available'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'waste_category_id' => Yii::t('app', 'Waste Category ID'),
            'building_type_available' => Yii::t('app', 'Building Type Available'),
            'is_public' => Yii::t('app', 'Is Public'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getAllQuery() {
        return static::find()->where(['waste_collection_interval.status'=>1])->andWhere(['waste_collection_interval.is_public'=>1])->orderby('waste_collection_interval.sort_order ASC');

    }

  public static function findByName($name,$categoryId) {
    $qry = static::find()->where(['waste_collection_interval.status'=>1])->andWhere(['name'=>$name,'waste_category_id'=>$categoryId]);
    return $qry;
  }
     public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('waste_collection_interval', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
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
}
