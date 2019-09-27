<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "parliament_constituency".
 *
 * @property int $id
 * @property string $name
 * @property int $constituency_type 1. Rajya Sabha 2.Lok Sabha
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ParliamentConstituency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parliament_constituency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'constituency_type','state_id'], 'required'],
            [['constituency_type', 'status','state_id'], 'integer'],
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
            'constituency_type' => Yii::t('app', 'Contituency Type'),
            'state.name' => Yii::t('app', 'State'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteParliamentConstituency($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('parliament_constituency', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
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

}
