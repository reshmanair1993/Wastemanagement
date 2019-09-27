<?php

namespace api\modules\v1\models;

use Yii;

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
            [['name'], 'required'],
            [['waste_category_id', 'status','is_public','collection_available','facility_provided_by_system'], 'integer'],
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
            'waste_category_id' => Yii::t('app', 'Waste Category'),
            'is_public' => Yii::t('app', 'Public'),
            'collection_available' => Yii::t('app', 'Collection Available'),
            'fkCategory.name' => Yii::t('app', 'Waste Category'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
	public function getAllQuery() {
		return static::find()->where(['waste_collection_method.status'=>1])->andWhere(['waste_collection_method.is_public'=>1])->orderby('waste_collection_method.sort_order ASC');

	}

  public static function findByName($name,$categoryId) {
    $qry = static::find()->where(['waste_collection_method.status'=>1])->andWhere(['name'=>$name,'waste_category_id'=>$categoryId]);
    return $qry;
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
}
