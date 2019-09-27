<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "state".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at','sort_order'], 'safe'],
            [['name', 'code'], 'string', 'max' => 250],
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
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteState($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('state', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
