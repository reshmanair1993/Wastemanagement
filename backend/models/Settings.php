<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "building_type".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_image
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['about_eng'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['about_eng','about_ml'], 'string'],
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
            'about_eng' => Yii::t('app', 'About English'),
            'about_ml' => Yii::t('app', 'About Malayalam'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteSettings($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('settings', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
