<?php

namespace api\modules\v1\models;


use Yii;

/**
 * This is the model class for table "waste_category".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class WasteCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waste_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 200],
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
    public function deleteWasteCategory($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('waste_category', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
	
	public static function getAllQuery() {
		return static::find(['status'=>1]);
	}
}
