<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $name
 * @property int $fk_state
 * @property string $created_at
 * @property string $modified_at
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','state_id'], 'required'],
            [['state_id','status'], 'integer'],
            [['created_at', 'modified_at','sort_order'], 'safe'],
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
            'state_id' => Yii::t('app', 'State'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getStates()
    {
        $states =  State::find()->where(['status'=> 1])->all();
        return $states;
    }
     public function getState()
      {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
      }
       public function deleteDistrict($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('district', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
